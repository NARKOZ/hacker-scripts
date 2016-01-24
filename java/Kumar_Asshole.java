
import java.io.File;
import java.io.FileInputStream;
import java.util.*;

import javax.mail.*;
import javax.mail.internet.*;
import javax.mail.search.FlagTerm;

/* Before running:
       U need  to  add Javax Mail JAR. 
*/

public class Kumar_Asshole {

	public static void main(String[] args) {
		Kumar_Asshole asshole = new Kumar_Asshole();
		gmail.read_and_reply();
	}

	public void read_and_reply() {
		Properties props = new Properties();
//change the below properties to configure your own.
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
					

					if (matcher.find()) {

						Properties props1 = new Properties();
						Address[] tomail;

						MimeMessage msg = new MimeMessage(session);

						try {
							msg.setFrom(new InternetAddress(username));
						} catch (MessagingException e1) {
							
							e1.printStackTrace();
						}

						try {
							tomail = messages[i].getFrom();
							String t1 = tomail[0].toString();

							msg.addRecipient(Message.RecipientType.TO, new InternetAddress(t1));
						} catch (AddressException e1) {
							
							e1.printStackTrace();
						} catch (MessagingException e1) {
						
							e1.printStackTrace();
						}

						
						try {
							msg.setSubject("Database fixes");
						} catch (MessagingException e1) {
							
							e1.printStackTrace();
						}

						
						try {
							msg.setText("No problem. I've fixed it. \n\n Please be careful next time.");

						} catch (MessagingException e1) {
							
							e1.printStackTrace();
						}

						Transport t = null;
						try {

							t = session.getTransport("smtps");
						} catch (NoSuchProviderException e) {
							
							e.printStackTrace();
						}
						try {
							try {
								t.connect(host, username, password);
							} catch (MessagingException e) {
								
								e.printStackTrace();
							}

							t.sendMessage(msg, msg.getAllRecipients());
						} catch (MessagingException e) {

							e.printStackTrace();

						} finally {
						}
					}

				}

			}
			inbox.close(true);
			store.close();

		} catch (Exception e) {
			e.printStackTrace();
		}
	}

}