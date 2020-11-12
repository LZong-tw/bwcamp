import java.util.Properties;

import javax.mail.Message;
import javax.mail.MessagingException;
import javax.mail.PasswordAuthentication;
import javax.mail.Session;
import javax.mail.Transport;
import javax.mail.internet.InternetAddress;
import javax.mail.internet.MimeMessage;


public class MailAgent {
	String host = "smtp.gmail.com";
	int port = 587;
	final String username = "tpce25453788@gmail.com";
	final String password = "a25453788!";//your password
	Properties props = new Properties();
	Message message;
	Transport transport;
	
	public MailAgent() {
		props.put("mail.smtp.host", host);
		props.put("mail.smtp.auth", "true");
		props.put("mail.smtp.starttls.enable", "true");
		props.put("mail.smtp.port", port);
		
		Session session = Session.getDefaultInstance(props,
			new javax.mail.Authenticator() {
				protected PasswordAuthentication getPasswordAuthentication() {
					return new PasswordAuthentication(username, password);
				}
			});
		
		try {

			message = new MimeMessage(session);
			message.setFrom(new InternetAddress("tpce25453788@gmail.com"));
			message.setRecipients(Message.RecipientType.TO, InternetAddress.parse("ivergaccc@yahoo.com.tw,happy3842@gmail.com"));
			message.setSubject("中階被經會考 - 銀行端資料自動化程式執行結果");
			//message.setText("Dear Levin, \n\n 測試 測試 測試 測試 測試 測試 email !");

			transport = session.getTransport("smtp");
			transport.connect(host, port, username, password);

			//transport.send(message);
			//System.out.println("寄送email結束.");

		} catch (MessagingException e) {
			throw new RuntimeException(e);
		}
	}
	
	@SuppressWarnings("static-access")
	public void send(String subject, String content) {
		try {
			message.setSubject(subject);
			message.setText(content);
			transport.send(message);
		} catch (MessagingException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}
	}
}