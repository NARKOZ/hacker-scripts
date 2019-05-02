import java.io.BufferedReader
import java.io.InputStreamReader
import java.io.PrintWriter
import java.net.Socket

private const val MY_USERNAME = "my_username"
private const val PASSWORD_PROMPT = "Password: "
private const val PASSWORD = "1234"
private const val COFFEE_MACHINE_IP = "10.10.42.42"
private const val DELAY_BEFORE_BREW = 17
private const val DELAY = 24

fun main(args: Array<String>) {
    for (i in 1 until args.size) {
        if (!args[i].contains(MY_USERNAME)) {
            return
        }
    }
    val telnet = Socket(COFFEE_MACHINE_IP, 23)
    val out = PrintWriter(telnet.getOutputStream(), true)
    val reader = BufferedReader(InputStreamReader(telnet.getInputStream()))
    Thread.sleep((DELAY_BEFORE_BREW * 1000).toLong())
    if (reader.readLine() != PASSWORD_PROMPT) {
        return
    }
    out.println(PASSWORD)
    out.println("sys brew")
    Thread.sleep((DELAY * 1000).toLong())
    out.println("sys pour")
    out.close()
    reader.close()
    telnet.close()
}