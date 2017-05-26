package chat;

import java.sql.SQLException;

import org.apache.commons.dbcp.BasicDataSource;

public class Data {
	 
	
	private static BasicDataSource ds = null;
	
 	public static BasicDataSource getDataSource() throws SQLException {
                if (ds == null) {
                        ds = new BasicDataSource();
                        /*ds.setDriverClassName(ApplicationProperties.DATABASE_DRIVER);
                        ds.setUsername(ApplicationProperties.DATABASE_USERNAME);
                        ds.setPassword(ApplicationProperties.DATABASE_PASSWORD);
                        ds.setUrl(ApplicationProperties.DATABASE_URL);
                        ds.setMaxActive(40);
                        ds.setMaxWait(-1);
                        ds.setMaxIdle(20);
                        DATASOURCE.info("Datasource is null");
                        */


                        ds.setDriverClassName("com.mysql.jdbc.Driver");
                        ds.setUsername("user");
                        ds.setPassword("CLDLRTa9");
                        ds.setUrl("jdbc:mysql://10.208.67.37:3306/bot_jeevansathi");
                        ds.setMaxActive(11);
                        ds.setMaxWait(-1);
                        ds.setMaxIdle(5);

                }

        return ds;
    }
	 
}
