import com.twilio.sdk.TwilioRestClient
import com.twilio.sdk.TwilioRestException
import com.twilio.sdk.resource.factory.MessageFactory
import com.twilio.sdk.resource.instance.Message
import org.apache.http.NameValuePair
import org.apache.http.message.BasicNameValuePair

import java.util.ArrayList
import java.util.Random

//Pre-requisite apache http and twilio java libraries

private const val ACCOUNT_SID = System.getenv("TWILIO_ACCOUNT_SID")
private const val AUTH_TOKEN = System.getenv("TWILIO_AUTH_TOKEN")

private const val YOUR_NUMBER = "1231231231"
private const val HER_NUMBER = "3213213213"

private val randomMessages = arrayOf(
    "Working hard",
    "Gotta ship this feature",
    "Someone fucked the system again"
)


@Throws(TwilioRestException::class)
fun main() {

    val client = TwilioRestClient(ACCOUNT_SID, AUTH_TOKEN)

    val finalMessage = randomMessages.random()

    val params = mutableListOf<NameValuePair>().apply {
        add(BasicNameValuePair("Body", "Late at work. $finalMessage"))
        add(BasicNameValuePair("From", YOUR_NUMBER))
        add(BasicNameValuePair("To", HER_NUMBER))
    }

    val messageFactory = client.getAccount().getMessageFactory()
    val message = messageFactory.create(params)
    System.out.println(message.getSid())
}