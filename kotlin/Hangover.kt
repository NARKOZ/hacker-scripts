import com.twilio.sdk.TwilioRestClient
import com.twilio.sdk.TwilioRestException
import com.twilio.sdk.resource.factory.MessageFactory
import com.twilio.sdk.resource.instance.Message
import org.apache.http.NameValuePair
import org.apache.http.message.BasicNameValuePair

import java.util.ArrayList
import java.util.Random

private val ACCOUNT_SID = System.getenv("TWILIO_ACCOUNT_SID")
private val AUTH_TOKEN = System.getenv("TWILIO_AUTH_TOKEN")

private const val YOUR_NUMBER = "1231231231"
private const val BOSS_NUMBER = "3213213213"

private val randomMessages = arrayOf(
    "Locked out",
    "Pipes broke",
    "Food poisoning",
    "Not feeling well"
)


fun main() {

    val client = TwilioRestClient(ACCOUNT_SID, AUTH_TOKEN)

    val finalMessage = randomMessages.random()

    val params = ArrayList<NameValuePair>().apply {
        add(BasicNameValuePair("Body", "Gonna work from home. $finalMessage"))
        add(BasicNameValuePair("From", YOUR_NUMBER))
        add(BasicNameValuePair("To", BOSS_NUMBER))
    }

    val messageFactory = client.getAccount().getMessageFactory()
    val message = messageFactory.create(params)
    System.out.println(message.getSid())
}

