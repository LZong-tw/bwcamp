package util.star.web;

import java.util.Vector;

import javax.servlet.ServletContext;

import org.zkoss.zk.ui.Sessions;

public class BillBarcodeGenerator {
	@SuppressWarnings("deprecation")
	private ServletContext sc = (ServletContext) Sessions.getCurrent().getWebApp().getNativeContext();
	private String 銷帳流水號;
	
	private String[] 英文Array = {
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", 
			"N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"};
	
	private String[] 數字Array = {
			"1", "2", "3", "4", "5", "6", "7", "8", "9", "1", "2", "3", "4", 
			"5", "6", "7", "8", "9", "2", "3", "4", "5", "6", "7", "8", "9"};
	
	public BillBarcodeGenerator(String 銷帳流水號) {
		this.銷帳流水號 = 銷帳流水號;
	}
	
	public String get超商第一段條碼() {
		String 超商第一段條碼 = "";
		
		// 民國年月日繳費期限6碼 + 代收代號3碼
		超商第一段條碼 = sc.getInitParameter("繳費期限") + sc.getInitParameter("超商代收代號");
		
		return 超商第一段條碼;
	}
	
	public String get超商第二段條碼() {
		String 超商第二段條碼 = "";
		
		// 00 + 7碼收款人帳戶 + 6碼銷帳流水號 + 銀行檢查碼
		String 前13碼 = sc.getInitParameter("7碼收款人帳戶") + 銷帳流水號;
		超商第二段條碼 = 前13碼 + this.getBankCheckCode(前13碼, sc.getInitParameter("銀行檢查碼權數"));
		
		return "00" + 超商第二段條碼;
	}
	
	@SuppressWarnings({ "unchecked", "rawtypes" })
	public String get超商第三段條碼(int 報名費) {
		String 超商第三段條碼 = "";
		
		// 4碼應繳月日
		String 應繳月日 = sc.getInitParameter("應繳日期");
		
		// 2碼超商校對碼 
		String 超商校對碼 = "**";
		
		// 9碼應繳金額
		String 應繳金額 = "";
		Vector 應繳金額Vector = new Vector();
		//char[] 金額CharArray = sc.getInitParameter("報名費用").toCharArray();
		String s報名費 = "" + 報名費;
		char[] 金額CharArray = s報名費.toCharArray();
		for(int i=0; i<金額CharArray.length; i++) {
			應繳金額Vector.add(金額CharArray[i]);
		}
		int size = 9-應繳金額Vector.size();
		for(int i=0; i<size; i++) {
			應繳金額Vector.add(0, "0");
		}
		for(Object obj : 應繳金額Vector) {
			應繳金額 += obj.toString();
		}
		
		// 校對碼
		String 超商第一段條碼 = this.get超商第一段條碼();
		String 超商第二段條碼 = this.get超商第二段條碼();
		超商第三段條碼 = 應繳月日 + 應繳金額;
		
//		超商第一段條碼 = "991231Y01";
//		超商第二段條碼 = "ABCDEFGHIKLMNPQR";
//		超商第三段條碼 = "1234000007890";
//		System.out.println("超商第一段條碼 = " + 超商第一段條碼);
//		System.out.println("超商第二段條碼 = " + 超商第二段條碼);
//		System.out.println("超商第三段條碼 = " + 超商第三段條碼);
		
		Vector 超商第一段條碼Vector = new Vector();
		Vector 超商第二段條碼Vector = new Vector();
		Vector 超商第三段條碼Vector = new Vector();
		for(int i=0; i<超商第一段條碼.toCharArray().length;i ++) {
			超商第一段條碼Vector.add(超商第一段條碼.toCharArray()[i]);
		}
		for(int i=0; i<超商第二段條碼.toCharArray().length;i ++) {
			超商第二段條碼Vector.add(超商第二段條碼.toCharArray()[i]);
		}
		for(int i=0; i<超商第三段條碼.toCharArray().length;i ++) {
			超商第三段條碼Vector.add(超商第三段條碼.toCharArray()[i]);
		}
		
		// 校對碼第一碼 : 3段條碼之奇數碼加總值除以11之餘數 (0=A，10=B)
		int 奇數碼加總 = 0;
		for(int i=1; i<=超商第一段條碼Vector.size(); i++) {
			if(i % 2 == 1) {
				String value = 超商第一段條碼Vector.get(i-1).toString();
				for(int j=0; j<英文Array.length; j++) {
					if(英文Array[j].equals(value)) {
						value = 數字Array[j];
					}
				}
				奇數碼加總 += Integer.parseInt(value);
			}
		}
		for(int i=1; i<=超商第二段條碼Vector.size(); i++) {
			if(i % 2 == 1) {
				String value = 超商第二段條碼Vector.get(i-1).toString();
				for(int j=0; j<英文Array.length; j++) {
					if(英文Array[j].equals(value)) {
						value = 數字Array[j];
					}
				}
				奇數碼加總 += Integer.parseInt(value);
			}
		}
		for(int i=1; i<=超商第三段條碼Vector.size(); i++) {
			if(i % 2 == 1) {
				String value = 超商第三段條碼Vector.get(i-1).toString();
				for(int j=0; j<英文Array.length; j++) {
					if(英文Array[j].equals(value)) {
						value = 數字Array[j];
					}
				}
				奇數碼加總 += Integer.parseInt(value);
			}
		}
		String 校對碼第一碼 = "";
		校對碼第一碼 = "" + (奇數碼加總 % 11);
		if(校對碼第一碼.equals("0")) {
			校對碼第一碼 = "A";
		} else if(校對碼第一碼.equals("10")) {
			校對碼第一碼 = "B";
		}
		
		// 校對碼第二碼 : 3段條碼之偶數碼加總值除以11之餘數 (0=A，10=B)
		int 偶數碼加總 = 0;
		for(int i=1; i<=超商第一段條碼Vector.size(); i++) {
			if(i % 2 == 0) {
				String value = 超商第一段條碼Vector.get(i-1).toString();
				for(int j=0; j<英文Array.length; j++) {
					if(英文Array[j].equals(value)) {
						value = 數字Array[j];
					}
				}
				偶數碼加總 += Integer.parseInt(value);
			}
		}
		for(int i=1; i<=超商第二段條碼Vector.size(); i++) {
			if(i % 2 == 0) {
				String value = 超商第二段條碼Vector.get(i-1).toString();
				for(int j=0; j<英文Array.length; j++) {
					if(英文Array[j].equals(value)) {
						value = 數字Array[j];
					}
				}
				偶數碼加總 += Integer.parseInt(value);
			}
		}
		for(int i=1; i<=超商第三段條碼Vector.size(); i++) {
			if(i % 2 == 0) {
				String value = 超商第三段條碼Vector.get(i-1).toString();
				for(int j=0; j<英文Array.length; j++) {
					if(英文Array[j].equals(value)) {
						value = 數字Array[j];
					}
				}
				偶數碼加總 += Integer.parseInt(value);
			}
		}
		String 校對碼第二碼 = "";
		校對碼第二碼 = "" + (偶數碼加總 % 11);
		if(校對碼第二碼.equals("0")) {
			校對碼第二碼 = "X";
		} else if(校對碼第二碼.equals("10")) {
			校對碼第二碼 = "Y";
		}
		
		超商校對碼 = 校對碼第一碼 + 校對碼第二碼;
		
		//System.out.println("超商校對碼 = " + 超商校對碼);
		
		// 4碼應繳月日 + 2碼超商校對碼 + 9碼應繳金額
		超商第三段條碼 = 應繳月日 + 超商校對碼 + 應繳金額;
		
		return 超商第三段條碼;
	}
	
	public String get銀行第二段條碼() {
		String 銀行第二段條碼 = "";
		
		// 00 + 7碼收款人帳戶 + 6碼銷帳流水號 + 銀行檢查碼
		String 前13碼 = sc.getInitParameter("7碼收款人帳戶") + 銷帳流水號;
		銀行第二段條碼 = 前13碼 + this.getBankCheckCode(前13碼, sc.getInitParameter("銀行檢查碼權數"));
		
		return 銀行第二段條碼;
	}
	
	public String get銀行第三段條碼(int 報名費) {
		return "" + 報名費;
	}
	
	@SuppressWarnings({ "rawtypes", "unchecked" })
	public int getBankCheckCode(String 流水帳號, String 銀行檢查碼權數) {
		int bankCheckCode = 10;
		try{
//			System.out.println("13碼 = " + 流水帳號);
//			System.out.println("銀行檢查碼權數 = " + 銀行檢查碼權數);
			// 13碼
			char[] 流水帳號CharArray = 流水帳號.toCharArray();
			Vector 流水帳號Vector = new Vector();
			for(int i=0; i<流水帳號CharArray.length; i++) {
				流水帳號Vector.add(流水帳號CharArray[i]);
			}
			
			if(流水帳號Vector.size() != 13) {
				return bankCheckCode;
			}
			
			// 拆去第三碼，剩12碼
			流水帳號Vector.remove(2);
			
//			String aaa = "";
//			for(Object obj : 流水帳號Vector) {
//				aaa += obj.toString();
//			}
//			System.out.println("12碼 = " + aaa);
			
			// 乘上權數
			char[] 銀行檢查碼權數CharArray = 銀行檢查碼權數.toCharArray();
			Vector 銀行檢查碼權數Vector = new Vector();
			for(int i=0; i<銀行檢查碼權數CharArray.length; i++) {
				銀行檢查碼權數Vector.add(銀行檢查碼權數CharArray[i]);
			}
			
			if(流水帳號Vector.size() != 12 || 銀行檢查碼權數Vector.size() != 12) {
				return bankCheckCode;
			}
			
			Vector 乘積Vector = new Vector();
			for(int i=0; i<流水帳號Vector.size(); i++) {
				乘積Vector.add(Integer.parseInt(流水帳號Vector.get(i).toString()) * Integer.parseInt(銀行檢查碼權數Vector.get(i).toString()));
			}
			
//			aaa = "";
//			for(Object obj : 乘積Vector) {
//				aaa += obj.toString();
//			}
//			System.out.println("乘積 = " + aaa);
			
			// 取個位數
			Vector 乘積取個位數Vector = new Vector();
			for(int i=0; i<乘積Vector.size(); i++) {
				Object obj = 乘積Vector.get(i);
				int 個位數 = Integer.parseInt(obj.toString()) % 10;
				乘積取個位數Vector.add(個位數);
			}
			
//			aaa = "";
//			for(Object obj : 乘積取個位數Vector) {
//				aaa += obj.toString();
//			}
//			System.out.println("取個位數 = " + aaa);
			
			// 總和
			int 總和 = 0;
			for(int i=0; i<乘積取個位數Vector.size(); i++) {
				總和 += Integer.parseInt(乘積取個位數Vector.get(i).toString());
			}
			
			//System.out.println("總和 = " + 總和);
			
			// 再取個位數
			int 個位數 = 總和 % 10;
			
			//System.out.println("取個位數 = " + 個位數);
			
			// 最後取10的補數
			if(個位數 == 0) {
				bankCheckCode = 0;
			} else {
				bankCheckCode = 10 - 個位數;
			}
			
			//System.out.println("10的補數 = " + bankCheckCode);
			
			return bankCheckCode;
		} catch(Exception e) {
			return bankCheckCode;
		}
	}
}