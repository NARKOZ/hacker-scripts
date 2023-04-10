use std::{env, process::Command};
use dotenv::dotenv;
use rand::seq::SliceRandom;
use openapi::apis::{
    configuration::Configuration, 
    default_api::{self as twilio_api, CreateMessageParams},
};

#[tokio::main]
async fn main() {
    dotenv().expect("Error reading .env file");
    let my_number = env::var("MY_NUMBER").expect("oof");
    let her_number = env::var("HER_NUMBER").unwrap();
    let api_key_sid = env::var("API_KEY_SID").unwrap();
    let api_key_secret = env::var("API_KEY_SECRET").unwrap();
    let account_sid = env::var("ACCOUNT_SID").unwrap();

    //Exit early if no sessions with my username are found
    let users = Command::new("who").output().unwrap();
    let is_active = String::from_utf8_lossy(&users.stdout)
        .split('\n')
        .any(|line| line.starts_with("username"));
    if !is_active { 
        std::process::exit(0); 
    }

    //Create message
    let reasons: Vec<&str> = vec!["Working hard", 
                                  "Gotta ship this feature",
                                  "Someone fucked the system again"];
    let random_reason: &str = reasons.choose(&mut rand::thread_rng()).unwrap();
    let msg = format!("Late at work. {}", random_reason);

    //Send a text message
    let twilio_config = Configuration {
        basic_auth: Some((api_key_sid, Some(api_key_secret))),
        ..Default::default()
    };

    let msg_params = CreateMessageParams {
        account_sid,
        to: my_number,
        from: Some(her_number),
        body: Some(msg.into()),
        ..Default::default()
    };

    let send_msg = twilio_api::create_message(&twilio_config, msg_params).await;
    match send_msg {
        Ok(result) => result,
        Err(error) => panic!("Error sending message: {}", error),
    };
}
