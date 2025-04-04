import java.io.File;

public class Bank {
	public static final String MailSubject = "中階會考-銀行資料自動化程式執行結果";
	public static String MailContent = "";
	
	@SuppressWarnings({ "static-access" })
	public static void main(String[] args) {
		//JOptionPane.showMessageDialog(null, "123");
		
		PropertiesFileParsing properties = new PropertiesFileParsing("Properties.txt");
		
		FTPHandler ftpHander = new FTPHandler(properties);
		if(ftpHander.downloadFTPFile(properties.FTPIP, properties.FTPPort, properties.FTPAccount, properties.FTPPassword, false, 
				properties.FTPRemotePath, properties.FTPFileName, properties.FTPLocalPath)) {
			//JOptionPane.showMessageDialog(null, "YA");
			new BankFileParsing(
					properties.FTPLocalPath + File.separator + properties.FTPFileName
					).start(properties.DBDriver, properties.DBURL, properties.DBAccount, properties.DBPassword, properties.DBInsertTable, properties.DBUpdateTable);
		} else {
			//JOptionPane.showMessageDialog(null, "Fail");
		}
		
		Bank.MailContent += "\n" + "任務結束...";
    	System.out.println("任務結束...");
		
		new MailAgent().send(MailSubject, MailContent);
		//JOptionPane.showMessageDialog(null, "123");
		//System.out.println("Gogogogogogogo"); 
		System.exit(0);
	}
}