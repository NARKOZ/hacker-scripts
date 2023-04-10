use std::{
    io::{Read, Write},
    net::TcpStream,
    process::Command,
    thread,
    time::Duration,
};

const COFFEE_MACHINE_IP: &str = "10.10.42.42:23";
const COFFEE_MACHINE_PASSWORD: &str = "1234";

fn main() -> std::io::Result<()> {
    //Exit early if no sessions with my username are found
    let users = Command::new("who").output().unwrap();
    let is_active = String::from_utf8_lossy(&users.stdout)
        .split('\n')
        .any(|line| line.starts_with("username"));
    if !is_active {
        std::process::exit(0);
    }

    //Brew fucking coffee
    let delay_before_brew = Duration::from_secs(17);
    let delay = Duration::from_secs(24);

    thread::sleep(delay_before_brew);
    let mut conn = TcpStream::connect(COFFEE_MACHINE_IP)?;

    conn.write_all(COFFEE_MACHINE_PASSWORD.as_bytes())?;
    conn.read_exact(&mut [0u8; 1024])?;
    conn.write_all(b"sys brew")?;

    thread::sleep(delay);
    conn.write_all(b"sys pour")?;

    Ok(())
}
