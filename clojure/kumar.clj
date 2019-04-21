(ns hacker-scripts.kumar
  (:import
    (java.util Properties)
    (javax.mail Session Authenticator PasswordAuthentication Message$RecipientType Transport Folder Flags Flags$Flag)
    (javax.mail.internet MimeMessage InternetAddress)
    (javax.mail.search FlagTerm FromTerm AndTerm OrTerm SubjectTerm BodyTerm SearchTerm)))

(def host "smtp.gmail.com")
(def my-email "my-email@gmail.com")
(def my-password "my-gmail-password")
(def kumar-email "kumar@gmail.com")

(def seen-flag (Flags. (Flags$Flag/SEEN)))

(def unread-term (FlagTerm. seen-flag false))

(defn get-session []
  (let [authenticator (proxy [Authenticator] []
                        (getPasswordAuthentication []
                          (PasswordAuthentication. my-email my-password)))
        props (Properties.)]
    (.put props "mail.smtp.host" "smtp.gmail.com")
    (.put props "mail.smtp.port" "587")
    (.put props "mail.smtp.auth" "true")
    (.put props "mail.smtp.starttls.enable" "true")
    (.. Session (getInstance props authenticator))))

(defn get-inbox [session]
  (let [store (.getStore session "imaps")
        inbox (do
                (.connect store host my-email my-password)
                (.getFolder store "inbox"))]
    (.open inbox Folder/READ_WRITE)
    inbox))

(defn get-no-worries-message [session]
  (let [message (MimeMessage. session)]
    (.setFrom message (InternetAddress. my-email))
    (.addRecipient message Message$RecipientType/TO (InternetAddress. kumar-email))
    (.setSubject message "Database fixes")
    (.setText message "No worries mate, be careful next time")
    message))

(defn search-term [pattern]
  (OrTerm. (into-array SearchTerm [(SubjectTerm. pattern) (BodyTerm. pattern)])))

(defn any-of-search-term [& patterns]
  (OrTerm. (into-array (map search-term patterns))))

(defn from-term [addr]
  (FromTerm. (InternetAddress. addr)))

(defn get-unread-sos-from-kumar [inbox]
  (let [flag (AndTerm. (into-array SearchTerm [unread-term
                                               (from-term kumar-email)
                                               (any-of-search-term "help" "sorry" "trouble")]))]
    (.search inbox flag)))

(defn mark-as-read [inbox messages]
  (.setFlags inbox messages seen-flag true))

(defn kumar-asshole []
  (let [session (get-session)
        inbox (get-inbox session)
        unread-sos-from-kumar (get-unread-sos-from-kumar inbox)]
    (when (seq unread-sos-from-kumar)
      (mark-as-read inbox unread-sos-from-kumar)
      (Transport/send (get-no-worries-message session)))))
