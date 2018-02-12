package main

import (
	"fmt"
	"math/rand"
	"os"
	"os/exec"
	"strings"
	"time"
)

func main() {
	output1, err := exec.Command("who").Output()
	output2 := os.Getenv("USER")
	users := string(output1[:])
	current_user := string(output2[:])
	if !strings.Contains(users, current_user) {
		return
	}

	reasons := []string{"Working hard", "Gotta ship this feature", "Someone fucked the system again"}

	rand.Seed(time.Now().UTC().UnixNano())
	message := "Late at work. " + reasons[rand.Intn(len(reasons))]

	TWILIO_ACCOUNT_SID := string(os.Getenv("TWILIO_ACCOUNT_SID"))
	TWILIO_AUTH_TOKEN := string(os.Getenv("TWILIO_AUTH_TOKEN"))
	MY_NUMBER := string(os.Getenv("MY_NUMBER"))
	HER_NUMBER := string(os.Getenv("HER_NUMBER"))

	response, err := exec.Command("curl", "-fSs", "-u", TWILIO_ACCOUNT_SID+":"+TWILIO_AUTH_TOKEN, "-d", "From="+MY_NUMBER, "-d", "To="+HER_NUMBER, "-d", "Body="+message, "https://api.twilio.com/2010-04-01/Accounts/"+TWILIO_ACCOUNT_SID+"/Messages").Output()
	if err != nil {
		fmt.Printf("Failed to send SMS: %s", err)
		return
	}

	fmt.Printf("Message Sent Successfully with response: %s ", response)
}
