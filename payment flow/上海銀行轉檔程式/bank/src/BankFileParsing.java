import java.io.BufferedReader;
import java.io.CharArrayWriter;
import java.io.FileInputStream;
import java.io.FileNotFoundException;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.Statement;
import java.util.ArrayList;
import java.util.Iterator;
import java.util.Map;


public class BankFileParsing {
	private String filePath;
	
	public BankFileParsing(String filePath) {
		this.filePath = filePath;
	}
	
	class 入帳資料 {
		String 檔案日期;
		String 代收類別;
		String 入帳日期;
		String 繳費日期;
		String 銷帳編號;
		int 繳款金額;
		int 總金額;
		int 總筆數;
	}
	
	public void start(String 資料庫連線驅動, String 資料庫連線url, String 資料庫連線帳號, String 資料庫連線密碼, Map<String, String> insert資料表, Map<String, String> update資料表) {
		BufferedReader reader = null;
		try {
			// 讀檔
			/**
			 * 首筆 : 
			 *  錄別 X(01)1-1 = '1'
			 *  日期 X(08)2-9
			 *  
			 * 明細 : 
			 *  錄別 X(01)1-1 = '2'
			 *  代收類別 X(06)2-7
			 *  入帳日期 9(08)8-15
			 *  繳費日期 9(08)16-23
			 *  銷帳編號 X(16)24-39
			 *  繳款金額 9(09)40-48
			 * 
			 * 結尾 :
			 *  錄別 X(01)1-1 = '3'
			 *  總金額 9(14)2-15
			 *  總筆數 9(10)16-25
			 * 
			 * 
			 */
			
			Bank.MailContent += "\n" + "開始讀檔 : " + filePath;
        	System.out.println("開始讀檔 : " + filePath);
        	
			reader = new BufferedReader(new InputStreamReader(new FileInputStream(filePath), "UTF-8"));
			
			String 錄別 = "";
			String 檔案日期 = "";
			int 總金額 = 0;
			int 總筆數 = 0;
			
			String str = "";
			ArrayList<入帳資料> arrayList = new ArrayList<入帳資料>();
			
			while ((str = reader.readLine()) != null) {
				if(str.length() > 0) {
					錄別 = str.substring(0, 1);
					switch(Integer.parseInt(錄別)) { 
		            case 1: // 首筆
		            	if(str.length() == 9) {
		            		檔案日期 = str.substring(1, 9);
		            		System.out.println("BankFileParsing : 檔案日期 = " + 檔案日期);
		            	} else {
		            		System.out.println("BankFileParsing : 首筆 length != 9");
		            		Bank.MailContent += "\n" + "BankFileParsing : 首筆 length != 9";
		            	}
		                break;
		            case 2: // 明細
		            	if(str.length() == 48) {
		            		入帳資料 m入帳資料 = new 入帳資料();
		            		m入帳資料.代收類別 = str.substring(1, 7).trim();
		            		m入帳資料.入帳日期 = str.substring(7, 15);
		            		m入帳資料.繳費日期 = str.substring(15, 23);
		            		m入帳資料.銷帳編號 = str.substring(23, 39).trim();
		            		m入帳資料.繳款金額 = Integer.parseInt(str.substring(39, 48));
		            		arrayList.add(m入帳資料);
		            	} else {
		            		System.out.println("BankFileParsing : 明細 length != 48");
		            		Bank.MailContent += "\n" + "BankFileParsing : 明細 length != 48";
		            	} 
		                break; 
		            case 3: // 結尾
		            	if(str.length() == 25) {
		            		總金額 = Integer.parseInt(str.substring(1, 15));
		            		總筆數 = Integer.parseInt(str.substring(15, 25));
		            		System.out.println("BankFileParsing : 總金額 = " + 總金額);
		            		System.out.println("BankFileParsing : 總筆數 = " + 總筆數);
		            	} else {
		            		System.out.println("BankFileParsing : 明細 length != 25");
		            		Bank.MailContent += "\n" + "BankFileParsing : 明細 length != 25";
		            	} 
		                break;
					}
				}
			}
    		
			if(arrayList.size() > 0) {
				Class.forName(資料庫連線驅動).newInstance();
	    		Connection conn = DriverManager.getConnection(資料庫連線url, 資料庫連線帳號, 資料庫連線密碼);
	    		Statement stat = conn.createStatement();
	    		Statement stat2 = conn.createStatement();
				String sql = "";
				
    			if ((conn != null) && !conn.isClosed()) { // if DB 連線成功
    				// 先把 檔案日期 的刪掉
    				Iterator<String> it = insert資料表.keySet().iterator();
    				while(it.hasNext()) {
    					sql = "Delete From " + insert資料表.get(it.next()) 
        						+ " where 檔案日期='" + 檔案日期 + "'";
        				Bank.MailContent += "\n" + "刪除資料庫 : " + sql;
        	        	System.out.println("刪除資料庫 : " + sql);
        				stat.executeUpdate(sql);
    				}
    				
    				Bank.MailContent += "\n" + "開始寫入DB";
    	        	System.out.println("開始寫入DB");
    	        	
    	        	
    				for(入帳資料 s入帳資料 : arrayList) {
    					char[] 銷帳編號charArray = s入帳資料.銷帳編號.toCharArray();
    					String 活動代號 = "" + 銷帳編號charArray[7] + 銷帳編號charArray[8];
    					
    					if(insert資料表.containsKey(活動代號) && update資料表.containsKey(活動代號)) {
    						s入帳資料.檔案日期 = 檔案日期;
        					s入帳資料.總金額 = 總金額;
        					s入帳資料.總筆數 = 總筆數;
        					
        					// 寫入DB
        					sql = "Insert into " + insert資料表.get(活動代號) 
        							+ " (檔案日期"
        							+ ", 代收類別"
        							+ ", 入帳日期"
        							+ ", 繳費日期"
        							+ ", 銷帳編號"
        							+ ", 繳款金額"
        							+ ", 總金額"
        							+ ", 總筆數)"
        							+ "Values('" + s入帳資料.檔案日期 + "'" 
        							+ ", '" + s入帳資料.代收類別 + "'" 
        							+ ", '" + s入帳資料.入帳日期 + "'" 
        							+ ", '" + s入帳資料.繳費日期 + "'" 
        							+ ", '" + s入帳資料.銷帳編號 + "'" 
        							+ ", '" + s入帳資料.繳款金額 + "'" 
        							+ ", '" + s入帳資料.總金額 + "'" 
        							+ ", '" + s入帳資料.總筆數 + "')";
        					stat.executeUpdate(sql);
        					
        					// 更新報名資料的已繳費
        					sql = "Update " + update資料表.get(活動代號) + " set "
        							+ "繳費= '" + "已繳費" + "'" 
        							+ ", 繳款金額= '" + s入帳資料.繳款金額 + "'" 
    								+ " where 銷帳編號='" + s入帳資料.銷帳編號 + "'";
        					stat2.executeUpdate(sql);
    					}
    				}
    				
					if(conn != null) {
						conn.close();
					}
    			} else {
    				System.out.println("BankFileParsing : 資料庫連線失敗");
    				Bank.MailContent += "\n" + "BankFileParsing : 資料庫連線失敗";
    			}
			}
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
    	System.out.println("BankFileParsing : " + trace);
    	Bank.MailContent += "\n" + trace;
    	new MailAgent().send(Bank.MailSubject + " : 失敗", Bank.MailContent);
    }
}