import java.io.File;
import java.io.FileInputStream;
import java.util.*;
import java.util.regex.*;

import javax.mail.*;
import javax.mail.internet.*;
import javax.mail.search.FlagTerm;
//Dependencies- Java mail API 

public class KumarAsshole {

	public static void main(String[] args) {
		KumarAsshole asshole = new KumarAsshole();
		asshole.read();
	}

	public void read() {
		Properties props = new Properties();

		//modify below properties to your details
		String host = "smtp.gmail.com";
		String username = "yourmailaddress@example.com goes here";
		String password = "your password goes here ";
		String Kumar_mail = "the mail address to be replied to !";

		try {
			Session session = Session.getDefaultInstance(props, null);

			Store store = session.getStore("imaps");
			store.connect(host, username, password);

			Folder inbox = store.getFolder("inbox");
			inbox.open(Folder.READ_ONLY);

			Message messages[] = inbox.search(new FlagTerm(new Flags(Flags.Flag.SEEN), false));

			for (int i = 0; i < messages.length; i++) {
				if (messages[i].getFrom()[0].toString().contains(Kumar_mail)) {
					String bodytext = null;
					Object content = messages[i].getContent();
					if (content instanceof String) {
						bodytext = (String) content;
					} else if (content instanceof Multipart) {
						Multipart mp = (Multipart) content;

						BodyPart bp = mp.getBodyPart(mp.getCount() - 1);
						bodytext = (String) bp.getContent();
					}

					Pattern pattern = Pattern.compile("sorry|help|wrong", Pattern.CASE_INSENSITIVE);
					Matcher matcher = pattern.matcher(bodytext);
					// check all occurance

					if (matcher.find()) {
						Properties props1 = new Properties();
						Address[] tomail;

						MimeMessage msg = new MimeMessage(session);
						msg.setFrom(new InternetAddress(username));
						tomail = messages[i].getFrom();
						String t1 = tomail[0].toString();
						msg.addRecipient(Message.RecipientType.TO, new InternetAddress(t1));
						msg.setSubject("Database fixes");
						msg.setText("No problem. I've fixed it. \n\n Please be careful next time.");
						Transport t = null;
						t = session.getTransport("smtps");
						t.connect(host, username, password);
						t.sendMessage(msg, msg.getAllRecipients());
					}
				}
			}

			inbox.close(true);
			store.close();

		} catch(Exception e) {
			e.printStackTrace();
		}
	}
}
