(ns hacker-scripts.coffee
  (:require [environ.core :refer [env]])
  (:import
    (java.net Socket)
    (java.io BufferedReader PrintWriter InputStreamReader)))

(def my-username "my-username")
(def my-password "my-password")

(def coffee-machine-ip "10.10.42.42")
(def password-prompt "Password: ")
(def connection-port 23)

(def sec-delay-before-brew 17)
(def sec-delay-before-pour 24)

(defn logged-in? [] (= (:USER env) my-username))

(defn auth [in-stream out-stream]
  (if (= (.readLine in-stream) password-prompt)
    (.println out-stream my-password)
    (throw (RuntimeException.
             "Failed to authenticate with coffee machine"))))

(defn command-brew-pour [out-stream]
  (do
    (Thread/sleep (* 1000 sec-delay-before-brew))
    (.println out-stream "sys brew")
    (Thread/sleep (* 1000 sec-delay-before-pour))
    (.println out-stream "sys pour")))

(defn coffee []
  (if (logged-in?)
    (with-open [socket (Socket. coffee-machine-ip connection-port)
                out-stream (PrintWriter. (.getOutputStream socket) true)
                in-stream (BufferedReader. (InputStreamReader. (.getInputStream socket)))]
      (do
        (auth in-stream out-stream)
        (command-brew-pour out-stream)))))
