package main

import (
	"fmt"
	"log"
	"math/rand"
	"os"

	"github.com/codeskyblue/go-sh"
	"github.com/subosito/twilio"
)

const my_number string = "+xxxxx"
const boss_number string = "+yyyyy"

func main() {
	//exit if sessions with my username are found
	_, err := sh.Command("who").Command("grep", "my_username").Output()
	if err != nil {
		os.Exit(1)
	}

	//Grab Twilio ID and token from environment variables
	Account_Sid := os.Getenv("TWILIO_ACCOUNT_SID")
	Auth_Token := os.Getenv("TWILIO_AUTH_TOKEN")

	//create the reasons slice and append reasons to it
	reasons := make([]string, 0)
	reasons = append(reasons,
		"Locked out",
		"Pipes broke",
		"Food poisoning",
		"Not feeling well")

	// Initialize Twilio client and send message
	client := twilio.NewClient(Account_Sid, Auth_Token, nil)
	message := fmt.Sprint("Gonna work from home...", reasons[rand.Intn(len(reasons))])

	params := twilio.MessageParams{
		Body: message,
	}
	s, resp, err := client.Messages.Send(my_number, boss_number, params)

	if err == nil {
		log.Fatal(s, resp, err)
	}
}
