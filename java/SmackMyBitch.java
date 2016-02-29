import com.twilio.sdk.TwilioRestClient;
import com.twilio.sdk.TwilioRestException;
import com.twilio.sdk.resource.factory.MessageFactory;
import com.twilio.sdk.resource.instance.Message;
import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;

//Pre-requisite apache http and twilio java libraries


public class SmackMyBitch {

    public static final String ACCOUNT_SID = System.getenv("TWILIO_ACCOUNT_SID");
    public static final String AUTH_TOKEN = System.getenv("TWILIO_AUTH_TOKEN");

    public static final String YOUR_NUMBER = "1231231231";
    public static final String HER_NUMBER = "3213213213";

    public static void main(String[] args) throws TwilioRestException {

        TwilioRestClient client = new TwilioRestClient(ACCOUNT_SID, AUTH_TOKEN);

        String[] randomMessages = {
                "Working hard",
                "Gotta ship this feature",
                "Someone fucked the system again",
        };

        int randomIndex = new Random().nextInt(randomMessages.length);
        String finalMessage = (randomMessages[randomIndex]);

        List<NameValuePair> params = new ArrayList<NameValuePair>();
        params.add(new BasicNameValuePair("Body", "Late at work. " + finalMessage));
        params.add(new BasicNameValuePair("From", YOUR_NUMBER));
        params.add(new BasicNameValuePair("To", HER_NUMBER));

        MessageFactory messageFactory = client.getAccount().getMessageFactory();
        Message message = messageFactory.create(params);
        System.out.println(message.getSid());
    }
}