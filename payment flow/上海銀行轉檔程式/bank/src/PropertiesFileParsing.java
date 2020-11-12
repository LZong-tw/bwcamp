import java.io.BufferedReader;
import java.io.CharArrayWriter;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.text.SimpleDateFormat;
import java.util.Date;
import java.util.HashMap;
import java.util.Map;

public class PropertiesFileParsing {
	public static String FTPIP = "";
	public static String FTPPort = "";
	public static String FTPAccount = "";
	public static String FTPPassword = "";
	public static String FTPRemotePath = "";
	public static String FTPFileName = "";
	public static String FTPLocalPath = "";
	public static String DBDriver = "";
	public static String DBURL = "";
	public static String DBAccount = "";
	public static String DBPassword = "";
	public static Map<String, String> DBInsertTable = new HashMap<String, String>();
	public static Map<String, String> DBUpdateTable = new HashMap<String, String>();
	
	private enum PropertiesEnum {
		FTPIP, FTPPort, FTPAccount, FTPPassword, FTPRemotePath, FTPFileName, FTPLocalPath, DBDriver, DBURL, DBAccount, DBPassword, DBInsertTable, DBUpdateTable
	}
	
	@SuppressWarnings("static-access")
	public PropertiesFileParsing(String filePath) {
		BufferedReader reader = null;
		try {
			String absolutePath = this.getClass().getResource("").getPath();
			System.out.println("當前路徑" + " = " + absolutePath);
			Bank.MailContent += "\n" + "當前路徑" + " = " + absolutePath;
			
			reader = new BufferedReader(new InputStreamReader(new FileInputStream(absolutePath + filePath), "big5")); // 指定讀取文件的編碼格式，以免出現中文亂碼
			String str = null;
			String key = "";
			String value = "";
			while ((str = reader.readLine()) != null) {
				if(str.split("==").length == 2) {
					key = str.split("==")[0];
					value = str.split("==")[1];
				} else if(str.split("==").length == 1) {
					key = str.split("==")[0];
					value = "";
				} else {
					key = str;
					value = "";
				}
				
				System.out.println("PropertiesFileParsing : " + key + " = " + value);
				Bank.MailContent += "\n" + key + " = " + value;
				
				PropertiesEnum _PropertiesEnum = PropertiesEnum.valueOf(key);
				switch (_PropertiesEnum) {
					case FTPIP: 
						FTPIP = value;
						break;
					case FTPPort: 
						FTPPort = value;
						break;
					case FTPAccount: 
						FTPAccount = value;
						break;
					case FTPPassword: 
						FTPPassword = value;
						break;
					case FTPRemotePath: 
						FTPRemotePath = value;
						break;
					case FTPFileName: 
						FTPFileName = value;
						break;
					case FTPLocalPath: 
						FTPLocalPath = value;
						break;
					case DBDriver: 
						DBDriver = value;
						break;
					case DBURL: 
						DBURL = value;
						break;
					case DBAccount: 
						DBAccount = value;
						break;
					case DBPassword: 
						DBPassword = value;
						break;
					case DBInsertTable: 
						String[] insertDBs = value.split(";");
						for(String db : insertDBs) {
							String[] insertDB = db.split(":");
							DBInsertTable.put(insertDB[0], insertDB[1]);
						}
						break;
					case DBUpdateTable: 
						String[] updateDBs = value.split(";");
						for(String db : updateDBs) {
							String[] updateDB = db.split(":");
							DBUpdateTable.put(updateDB[0], updateDB[1]);
						}
						break;
			    }
			}
			
			// 修改FileName，前面加上yyyyMMdd
			this.FTPFileName = new SimpleDateFormat("yyyyMMdd").format(new Date()) + "-" + FTPFileName;
			System.out.println("PropertiesFileParsing : " + "FTPFileName" + " = " + FTPFileName);
			Bank.MailContent += "\n" + "FTPFileName" + " = " + FTPFileName;
		} catch (FileNotFoundException e) {
			handleException(e);
		} catch (IOException e) {
			handleException(e);
		} catch (Exception e) {
			handleException(e);
		} finally {
			try {
				reader.close();
			} catch (IOException e) {
				handleException(e);
			}
		}
	}
	
	public void handleException(Exception e) {
    	CharArrayWriter cw = new CharArrayWriter();
    	PrintWriter w = new PrintWriter(cw);
    	e.printStackTrace(w);
    	w.close();
    	String trace = cw.toString();
    	System.out.println("PropertiesFileParsing : " + trace);
    	Bank.MailContent += "\n" + trace;
    	new MailAgent().send(Bank.MailSubject + " : 失敗", Bank.MailContent);
    }
}