import com.twilio.sdk.TwilioRestClient;
import com.twilio.sdk.TwilioRestException;
import com.twilio.sdk.resource.factory.MessageFactory;
import com.twilio.sdk.resource.instance.Message;
import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class Hangover {

    public static final String ACCOUNT_SID = System.getenv("TWILIO_ACCOUNT_SID");
    public static final String AUTH_TOKEN = System.getenv("TWILIO_AUTH_TOKEN");

    public static final String YOUR_NUMBER = "1231231231";
    public static final String BOSS_NUMBER = "3213213213";

    public static void main(String[] args) throws TwilioRestException {

        TwilioRestClient client = new TwilioRestClient(ACCOUNT_SID, AUTH_TOKEN);

        String[] randomMessages = {
                "Locked out",
                "Pipes broke",
                "Food poisoning",
                "Not feeling well"
        };

        int randomIndex = new Random().nextInt(randomMessages.length);
        String finalMessage = (randomMessages[randomIndex]);

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new BasicNameValuePair("Body", "Gonna work from home. " + finalMessage));
        params.add(new BasicNameValuePair("From", YOUR_NUMBER));
        params.add(new BasicNameValuePair("To", BOSS_NUMBER));

        MessageFactory messageFactory = client.getAccount().getMessageFactory();
        Message message = messageFactory.create(params);
        System.out.println(message.getSid());
    }
}

