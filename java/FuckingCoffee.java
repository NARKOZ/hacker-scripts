import java.net.*;
import java.io.*;
 
public class FuckingCoffee{

    private static final String MY_USERNAME = "my_username";
    private static final String PASSWORD_PROMPT = "Password: ";
    private static final String PASSWORD = "1234";
    private static final String COFFEE_MACHINE_IP = "10.10.42.42";
    private static int DELAY_BEFORE_BREW = 17;
    private static int DELAY = 24;
    
    public static void main(String[] args)throws Exception{
        for(int i =  1; i< args.length ; i++){
            if(!args[i].contains(MY_USERNAME)){
                return;
            }
        }
        Socket telnet = new Socket(COFFEE_MACHINE_IP, 23);
        PrintWriter out = new PrintWriter(telnet.getOutputStream(), true);
        BufferedReader in = new BufferedReader(new InputStreamReader(telnet.getInputStream()));
        Thread.sleep(DELAY_BEFORE_BREW*1000);
        if(!in.readLine().equals(PASSWORD_PROMPT)){
            return ;
        }
        out.println(PASSWORD);
        out.println("sys brew");
        Thread.sleep(DELAY*1000);
        out.println("sys pour");
        out.close();
        in.close();
        telnet.close();
    }
}
