import java.io.CharArrayWriter;
import java.io.File;
import java.io.IOException;
import java.io.InputStream;
import java.io.PrintWriter;
import java.net.ConnectException;
import java.net.SocketException;
import java.security.NoSuchAlgorithmException;

import org.apache.commons.io.FileUtils;
import org.apache.commons.lang.StringUtils;
import org.apache.commons.net.ftp.FTP;
import org.apache.commons.net.ftp.FTPClient;
import org.apache.commons.net.ftp.FTPFile;
import org.apache.commons.net.ftp.FTPReply;
import org.apache.commons.net.ftp.FTPSClient;

public class FTPHandler {
	private PropertiesFileParsing properties;
	
	public FTPHandler(PropertiesFileParsing properties) {
		this.properties = properties;
	}
	
	/**
     * 建立FTP連線
     * @param ip FTP IP
     * @param port FTP PORT
     * @param id FTP 帳號
     * @param pwd FTP密碼
     * @param isSSL 是否透過SSL
     * @return
     * @throws IOException 
     * @throws SocketException 
     * @throws NumberFormatException 
     * @throws NoSuchAlgorithmException 
     */
    public static FTPClient createFtpConnection(String ip, String port, String id, String pwd, boolean isSSL) throws NumberFormatException, SocketException, IOException, NoSuchAlgorithmException{
        FTPClient ftpClient;
        if(isSSL){
            ftpClient = new FTPSClient();
        }else{
            ftpClient = new FTPClient();
        }
        // 登入ftp
        ftpClient.connect(ip, Integer.parseInt(port));
        if( !FTPReply.isPositiveCompletion(ftpClient.getReplyCode()) ){
            throw new RuntimeException("FTP連線失敗");
        }else{
            boolean ftpConnect = ftpClient.login(id, pwd);
            if(!ftpConnect){
                throw new RuntimeException("FTP登入失敗["+id+","+pwd+"]");
            }else{
                // 設定ftp
                ftpClient.enterLocalPassiveMode();
                ftpClient.setFileType(FTP.BINARY_FILE_TYPE);
                return ftpClient;
            }
        }
    }
	
	/**
     * 下載FTP檔案
     * @param ip FTP IP
     * @param port FTP PORT
     * @param id FTP 帳號
     * @param pwd FTP密碼
     * @param isSSL 是否透過SSL
     * @param dir FTP路徑
     * @param fileName 檔名
     * @param downloadPath 下載位置
     * @return 是否下載成功
     */
    @SuppressWarnings("static-access")
	public boolean downloadFTPFile(String ip, String port, String id, String pwd, boolean isSSL, String dir, String fileName, String downloadPath){
    	boolean downloadFile = false;
        FTPClient ftpClient = null;
        InputStream is = null;
        try{
            ftpClient = createFtpConnection(ip, port, id, pwd, isSSL);
            boolean changeDir;
            if(StringUtils.isBlank(dir)){//當不需要切換路徑
                changeDir = true;
            }else{
                changeDir = ftpClient.changeWorkingDirectory(dir);
            }
            if(changeDir){
            	Bank.MailContent += "\n" + "fileName = " + fileName;
            	System.out.println("fileName = " + fileName);
            	
                //下載檔案
            	boolean isFileFound = false;
            	if(ftpClient.listFiles().length > 0) {
            		FTPFile[] ftpFile = ftpClient.listFiles();
            		for(FTPFile ff : ftpFile) {
            			if(ff.getName().contains(fileName)) {
            				Bank.MailContent += "\n" + "檔案存在 : " + ff.getName();
                        	System.out.println("檔案存在 : " + ff.getName());
                            is = ftpClient.retrieveFileStream(ff.getName());
                            if(is==null) {//當檔案不存在
                            	Bank.MailContent += "\n" + "FTP下載檔案["+fileName+"]不存在";
                            	System.out.println("FTP下載檔案["+fileName+"]不存在");
                                //log.info("FTP下載檔案["+fileName+"]不存在");
                            }else{//當有檔案則轉成Stream
                            	//JOptionPane.showMessageDialog(null, downloadPath+fileName);
                            	properties.FTPFileName = ff.getName();
                            	Bank.MailContent += "\n" + downloadPath + "\\" + ff.getName();
                            	System.out.println(downloadPath + "\\" + ff.getName());
                                FileUtils.copyInputStreamToFile(is, new File(downloadPath + "\\" + ff.getName()));
                                downloadFile = true;
                                isFileFound = true;
                            }
            				break;
            			}
            		}
            	}
            	
            	if(!isFileFound) {
            		Bank.MailContent += "\n" + "FTP下載檔案["+fileName+"]不存在";
                	System.out.println("FTP下載檔案["+fileName+"]不存在");
            	}
            }else{
                //log.error("FTP目錄["+dir+"]不存在");
            	System.out.println("FTP目錄["+dir+"]不存在");
            	Bank.MailContent += "\n" + "FTP目錄["+dir+"]不存在";
            	new MailAgent().send(Bank.MailSubject + " : 失敗", Bank.MailContent);
            }
        } catch (ConnectException e) {
            //log.error("FTP連線失敗: "+e, e);
        	System.out.println("FTP連線失敗: " + e);
        	Bank.MailContent += "\n" + "FTP連線失敗";
        	handleException(e);
        } catch (Exception e) {
            //log.error("下載FTP資料失敗:"+e, e);
        	System.out.println("下載FTP資料失敗:" + e);
        	Bank.MailContent += "\n" + "下載FTP資料失敗";
            //log.error(e);
        	handleException(e);
        }finally{
            closeFtpConnection(ftpClient);//中斷FTP連線
            if(is!=null){
                try {
                    is.close();
                } catch (IOException e) {
                	handleException(e);
                }
            }
        }
        return downloadFile;
    }
    
	/**
     * 關閉FTP連線
     * @param ftpClient
     */
    public static void closeFtpConnection(FTPClient ftpClient){
        if( ftpClient!=null && ftpClient.isConnected() ){
            try {
                ftpClient.logout();
                ftpClient.disconnect();
            } catch (IOException e) {
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