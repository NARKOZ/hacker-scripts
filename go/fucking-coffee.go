package main

import (
	"fmt"
	"log"
	"os"
	"regexp"
	"time"

	"github.com/codeskyblue/go-sh"
	"github.com/google/goexpect"
)

func main() {
	// exit early if no sessions with my username are found
	currentUser, _ := sh.Command("who").Command("grep", "my_username").Output()
	if currentUser == nil {
		os.Exit(1)
	}

	// info about the coffee machine
	coffeeMachineIP := "10.10.42.42"
	password := "1234"
	passwordPrompt := "Password: "
	delayBeforeBrew := 17 * time.Second
	delay := 24 * time.Second

	// timeout for the telnet prompts
	timeout := 10 * time.Minute

	// sleep 17 seconds before brewing coffee
	time.Sleep(delayBeforeBrew)

	// spawn a new telnet session with the coffee machine
	t, _, err := expect.Spawn(fmt.Sprintf("telnet %s", coffeeMachineIP), -1)
	if err != nil {
		log.Fatal(err)
	}
	defer t.Close()

	t.Expect(regexp.MustCompile(passwordPrompt), timeout)
	t.Send(password + "\n")
	t.Expect(regexp.MustCompile("telnet>"), timeout)
	t.Send("sys brew\n")
	time.Sleep(delay)
	t.Expect(regexp.MustCompile("telnet>"), timeout)
	t.Send("sys pour\n")
	t.Expect(regexp.MustCompile("telnet>"), timeout)
	t.Send("exit\n")
}
