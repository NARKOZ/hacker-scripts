import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.Socket;
import java.util.concurrent.TimeUnit;
import java.util.stream.Collectors;

public class FuckingCoffee {

    private static final String PASSWORD_PROMPT = "Password: ";
    private static final String PASSWORD = "1234";
    private static final String COFFEE_MACHINE_IP = "10.10.42.42";
    private static final long DELAY_BEFORE_BREW = TimeUnit.SECONDS.toMillis(17);
    private static final long DELAY = TimeUnit.SECONDS.toMillis(24);

    public static void main(String... args) throws Exception {
        try (BufferedReader buffer = new BufferedReader(
                new InputStreamReader(Runtime.getRuntime().exec("who -q").getInputStream())
        )) {
            String whoOutput = buffer.lines().collect(Collectors.joining(System.lineSeparator()));
            if (!whoOutput.contains(System.getProperty("user.name"))) {
                return;
            }
        }

        try (Socket telnet = new Socket(COFFEE_MACHINE_IP, 23);
             BufferedReader in = new BufferedReader(new InputStreamReader(telnet.getInputStream()));
             PrintWriter out = new PrintWriter(telnet.getOutputStream(), true)
        ) {
            Thread.sleep(DELAY_BEFORE_BREW);
            if (!PASSWORD_PROMPT.equals(in.readLine())) {
                return;
            }
            out.println(PASSWORD);
            out.println("sys brew");
            Thread.sleep(DELAY);
            out.println("sys pour");
        }

    }
}
