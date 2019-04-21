(ns hacker-scripts.smack
  (:import
    (com.twilio Twilio)
    (com.twilio.rest.api.v2010.account Message)
    (com.twilio.type PhoneNumber)))

(def acc-sid "my twilio account SID")
(def acc-tkn "my twilio secret token")

(def my-num (PhoneNumber. "+10001112222"))
(def her-num (PhoneNumber. "+19998887777"))

(def reasons ["Working hard"
              "Gotta ship this feature"
              "Someone fucked the system again"])

(defn twilio-init []
  (Twilio/init acc-sid acc-tkn))

(defn send-sms [to-num from-num message]
  (.. Message (creator to-num from-num message) create))

(def send-sms-girlfriend (partial send-sms her-num my-num))

(defn smack []
  (twilio-init)
  (let [message (rand-nth reasons)]
    (send-sms-girlfriend message)))
