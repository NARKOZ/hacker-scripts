(ns hacker-scripts.hangover
  (:import
    (com.twilio Twilio)
    (com.twilio.rest.api.v2010.account Message)
    (com.twilio.type PhoneNumber)))

(def acc-sid "my twilio account SID")
(def acc-tkn "my twilio secret token")

(def my-num (PhoneNumber. "+10001112222"))
(def boss-num (PhoneNumber. "+19998887777"))

(def reasons ["Receiving delivery"
              "Waiting for repairman"
              "Nasty cold"])

(defn twilio-init []
  (Twilio/init acc-sid acc-tkn))

(defn send-sms [to-num from-num message]
  (.. Message (creator to-num from-num message) create))

(def send-sms-boss (partial send-sms boss-num my-num))

(defn hangover []
  (twilio-init)
  (let [message (rand-nth reasons)]
    (send-sms-boss message)))
