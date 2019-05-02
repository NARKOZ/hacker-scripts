import java.io.File
import java.io.FileInputStream
import java.util.*
import java.util.regex.*

import javax.mail.*
import javax.mail.internet.*
import javax.mail.search.FlagTerm

//modify below properties to your details
private const val host = "smtp.gmail.com"
private const val username = "yourmailaddress@example.com goes here"
private const val password = "your password goes here "
private const val Kumar_mail = "the mail address to be replied to !"


//Dependencies- Java mail API
fun main() {
    val asshole = KumarAsshole()
    asshole.read()
}

object KumarAsshole {

    fun read() {
        val props = Properties()

        try {

            val session = Session.getDefaultInstance(props, null)

            val store = session.getStore("imaps")
            store.connect(host, username, password)

            val inbox = store.getFolder("inbox")
            inbox.open(Folder.READ_ONLY)

            val messages = inbox.search(FlagTerm(Flags(Flags.Flag.SEEN), false))

            for (i in messages.indices) {

                if (messages[i].getFrom()[0].toString().contains(Kumar_mail)) {

                    var bodytext: String? = null
                    val content = messages[i].getContent()
                    if (content is String) {
                        bodytext = content

                    } else if (content is Multipart) {

                        val mp = content as Multipart

                        val bp = mp.getBodyPart(mp.getCount() - 1)
                        bodytext = bp.getContent()

                    }

                    val pattern = Pattern.compile("sorry|help|wrong", Pattern.CASE_INSENSITIVE)
                    val matcher = pattern.matcher(bodytext!!)
                    // check all occurance

                    if (matcher.find()) {

                        val props1 = Properties()
                        val tomail: Array<Address>

                        val msg = MimeMessage(session)
                        msg.setFrom(InternetAddress(username))
                        tomail = messages[i].getFrom()
                        val t1 = tomail[0].toString()
                        msg.addRecipient(Message.RecipientType.TO, InternetAddress(t1))
                        msg.setSubject("Database fixes")
                        msg.setText("No problem. I've fixed it. \n\n Please be careful next time.")
                        var t: Transport? = null
                        t = session.getTransport("smtps")
                        t!!.connect(host, username, password)
                        t!!.sendMessage(msg, msg.getAllRecipients())
                    }


                }
            }
            inbox.close(true)
            store.close()

        } catch (e: Exception) {
            e.printStackTrace()
        }

    }
}