import java.net.MalformedURLException;
import java.net.URL;

import org.codehaus.xfire.client.Client;

/**
 * Created by zhaohongyu on 16/5/11.
 */
public class WebServiceClient {
    public static void main(String[] args) throws MalformedURLException, Exception {
        //XFire client
        URL    url    = new URL("http://mylearngit.com/script/php/soap/wsdl/Server.php?wsdl");
        Client client = new Client(url);
        //第一个参数是方法名,后面的参数是需要传入的参数
        Object[] results  = client.invoke("Add", new Object[]{"1", "50"});
        Object[] results2 = client.invoke("myarr", new Object[]{"传递了一个字符串"});
        Object[] results3 = client.invoke("HelloWorld", new Object[]{"传递了一个字符串"});
        System.out.println((String) results[0]);
        System.out.println((String) results2[0]);
        System.out.println((String) results3[0]);
    }

}
