<?
namespace App\Services;

use Illuminate\Http\Request;

class PaymentflowService
{
    private $accountingSN, $request;
	
	private $alpabets = [
			"A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", 
			"N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];
	
	private $numbers = [
			"1", "2", "3", "4", "5", "6", "7", "8", "9", "1", "2", "3", "4", 
			"5", "6", "7", "8", "9", "2", "3", "4", "5", "6", "7", "8", "9"];
    
    public function __construct(Request $request){
        $this->request = $request;
    }
    
	public function BillBarcodeGenerator(String $accountingSN) {
		$this->accountingSN = $accountingSN;
	}
	
	public function getStoreFirstBarcode() {
        // 民國年月日繳費期限6碼 + 代收代號3碼
		return $this->request["繳費期限"] + $this->request["超商代收代號"];
	}
	
	public function getStoreSecondBarcode() {		
		// 00 + 7碼收款人帳戶 + 6碼銷帳流水號 + 銀行檢查碼
		$first13codes = $this->request["7碼收款人帳戶"] . $this->accountingSN;
		$storeSecondBarcode = $first13codes . $this->getBankCheckCode($first13codes, $this->request["銀行檢查碼權數"]);
		
		return "00" . $storeSecondBarcode;
	}
	
	public function getStoreThirdBarcode(int $fee) {		
		// 4碼應繳月日
		$deadline = $this->request["應繳日期"];
		
		// 2碼超商校對碼 
		$storeChecksumCode = "**";
		
		// 9碼應繳金額
		$shouldPay = "";
		$shouldPayArray = array();
		//char[] 金額CharArray = sc.getInitParameter("報名費用").toCharArray();
		$feeStr = "" . $fee;
		$feeCharArray = str_split($feeStr);
		for($i = 0; $i < count($feeCharArray); $i++) {
            array_push($shouldPayArray, $feeCharArray[$i]);
		}
		$size = 9 - count($shouldPayArray);
		for($i = 0; $i < $size; $i++) {
            array_unshift($shouldPayArray , 0);
		}
        foreach($shouldPayArray as $element) {
            $shouldPay .= $element;
        }
		
		// 校對碼
		$storeFirstBarcode = $this->getStoreFirstBarcode();
		$storeSecondBarcode = $this->getStoreSecondBarcode();
		$storeThirdBarcode = $deadline . $shouldPay;
		
//		超商第一段條碼 = "991231Y01";
//		超商第二段條碼 = "ABCDEFGHIKLMNPQR";
//		超商第三段條碼 = "1234000007890";
//		System.out.println("超商第一段條碼 = " + 超商第一段條碼);
//		System.out.println("超商第二段條碼 = " + 超商第二段條碼);
//		System.out.println("超商第三段條碼 = " + 超商第三段條碼);
		
        $storeFirstBarcodeArray = array();
		$storeSecondBarcodeArray = array();
		$storeThirdBarcodeArray = array();
        for($i = 0; $i < count(str_split($storeFirstBarcode)); $i++){
            array_push($storeFirstBarcodeArray, str_split($storeFirstBarcode)[$i]);
        }
        for($i = 0; $i < count(str_split($storeSecondBarcode)); $i++){
            array_push($storeSecondBarcodeArray, str_split($storeSecondBarcode)[$i]);
        }
        for($i = 0; $i < count(str_split($storeThirdBarcode)); $i++){
            array_push($storeThirdBarcodeArray, str_split($storeThirdBarcode)[$i]);
        }
		
		// 校對碼第一碼 : 3段條碼之奇數碼加總值除以11之餘數 (0=A，10=B)
        $oddSum = 0;
        for($i = 1; $i <= count($storeFirstBarcodeArray); $i++) {
            if($i % 2 == 1) {
				$value = $storeFirstBarcodeArray[$i - 1];
				for($j = 0; $j < count($this->alpabets); $j++) {
					if($this->alpabets[$j] == $value) {
						$value = $this->numbers[$j];
					}
				}
				$oddSum += $value;
			} 
        }
        for($i = 1; $i <= count($storeSecondBarcodeArray); $i++) {
            if($i % 2 == 1) {
				$value = $storeSecondBarcodeArray[$i - 1];
				for($j = 0; $j < count($this->alpabets); $j++) {
					if($this->alpabets[$j] == $value) {
						$value = $this->numbers[$j];
					}
				}
				$oddSum += $value;
			} 
        }
        for($i = 1; $i <= count($storeThirdBarcodeArray); $i++) {
            if($i % 2 == 1) {
				$value = $storeThirdBarcodeArray[$i - 1];
				for($j = 0; $j < count($this->alpabets); $j++) {
					if($this->alpabets[$j] == $value) {
						$value = $this->numbers[$j];
					}
				}
				$oddSum += $value;
			} 
        }
		
		$checkSumFirstElement = "" . ($oddSum % 11);
		if($checkSumFirstElement == "0") {
			$checkSumFirstElement = "A";
        }
        else if($checkSumFirstElement == "10") {
			$checkSumFirstElement = "B";
		}
		
		// 校對碼第二碼 : 3段條碼之偶數碼加總值除以11之餘數 (0=A，10=B)
		$evenSum = 0;
		for($i = 1; $i <= count($storeFirstBarcodeArray); $i++) {
			if($i % 2 == 0) {
				$value = $storeFirstBarcodeArray[$i - 1];
				for($j = 0; $j < count($this->alpabets); $j++) {
					if($this->alpabets[$j] == $value) {
						$value = $this->numbers[$j];
					}
				}
				$evenSum += $value;
			}
        }
        for($i = 1; $i <= count($storeSecondBarcodeArray); $i++) {
			if($i % 2 == 0) {
				$value = $storeSecondBarcodeArray[$i - 1];
				for($j = 0; $j < count($this->alpabets); $j++) {
					if($this->alpabets[$j] == $value) {
						$value = $this->numbers[$j];
					}
				}
				$evenSum += $value;
			}
        }
        for($i = 1; $i <= count($storeThirdBarcodeArray); $i++) {
			if($i % 2 == 0) {
				$value = $storeThirdBarcodeArray[$i - 1];
				for($j = 0; $j < count($this->alpabets); $j++) {
					if($this->alpabets[$j] == $value) {
						$value = $this->numbers[$j];
					}
				}
				$evenSum += $value;
			}
		}
		
		$checkSumSecondElement = "" + ($evenSum % 11);
		if($evenSum == "0") {
			$checkSumSecondElement = "X";
        } 
        else if($evenSum == "10") {
			$checkSumSecondElement =  "Y";
		}
		
		$storeChecksumCode = $checkSumFirstElement . $checkSumSecondElement;
		
		//System.out.println("超商校對碼 = " + 超商校對碼);
		
		// 4碼應繳月日 + 2碼超商校對碼 + 9碼應繳金額
		$storeThirdBarcode = $deadline . $storeThirdBarcode . $shouldPay;
		
		return $storeThirdBarcode;
	}
	
	public function getBankSecondBarcode() {		
		// 00 + 7碼收款人帳戶 + 6碼銷帳流水號 + 銀行檢查碼
		$first13codes = $this->request["7碼收款人帳戶"] . $this->accountingSN;
		
		return $first13codes . $this->getBankCheckCode($first13codes, $this->request["銀行檢查碼權數"]);
	}
	
	public function getBankThirdBarcode($fee) {
		return $fee;
	}
	
	public function getBankCheckCode(String $account, String $weight) {
		$bankCheckCode = 10;
		try{
//			System.out.println("13碼 = " + 流水帳號);
//			System.out.println("銀行檢查碼權數 = " + 銀行檢查碼權數);
			// 13碼
			$accountArray = str_split($account);
			// Vector 流水帳號Vector = new Vector();
			// for(int i=0; i<流水帳號CharArray.length; i++) {
			// 	流水帳號Vector.add(流水帳號CharArray[i]);
			// }
			
			if(count($accountArray) != 13) {
				return $bankCheckCode;
			}
			
			// 拆去第三碼，剩12碼
			unset($accountArray[2]);
			
//			String aaa = "";
//			for(Object obj : 流水帳號Vector) {
//				aaa += obj.toString();
//			}
//			System.out.println("12碼 = " + aaa);
			
			// 乘上權數
			$weightArray = str_split($weight);
			// Vector 銀行檢查碼權數Vector = new Vector();
			// for(int i=0; i<銀行檢查碼權數CharArray.length; i++) {
			// 	銀行檢查碼權數Vector.add(銀行檢查碼權數CharArray[i]);
			// }
			
			if(count($accountArray) != 12 || count($weightArray) != 12) {
				return $bankCheckCode;
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