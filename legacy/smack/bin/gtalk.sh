#JAVA_HOME=/usr/local/java
#PATH=$PATH:/usr/local/java/bin
#export CLASSPATH=$CLASSPATH:/usr/local/tomcat/common/lib/:/usr/local/smack/lib/:/usr/local/smack/:/usr/local/smack/lib/mysql-connector-java-5.1.7-bin.jar
#export CLASSPATH=$CLASSPATH:/usr/local/tomcat/common/lib/:/usr/local/smack/lib/serializer.jar:/usr/local/smack/lib/mysql-connector-java-5.1.7-bin.jar:/usr/local/smack/lib/xalan.jar:/usr/local/smack/lib/xercesImpl.jar:/usr/local/smack/lib/xml-apis.jar:/usr/local/smack/lib/xsltc.jar:/usr/local/smack
#CLASSPATH=$CLASSPATH:/usr/local/tomcat/common/lib/:/usr/local/smack/lib/smack.jar:/usr/local/smack/lib/mysql-connector-java-5.1.7-bin.jar:/usr/local/smack/lib/smackx.jar:/usr/local/smack/lib/smackx-debug.jar:/usr/local/smack/lib/smackx-jingle.jar:/usr/local/smack

#JAVA_HOME=/usr/lib/jvm/java-6-sun

#PATH=$PATH:/usr/lib/jvm/java-6-sun/bin
JAVA_HOME=/usr/local/jdk1.6.0_14

PATH=$PATH:/usr/local/jdk1.6.0_14/bin

current_directory=`pwd`

export CLASSPATH=$CLASSPATH:$current_directory/lib/smack.jar:$current_directory/lib/mysql-connector-java-5.1.7-bin.jar:$current_directory/lib/smackx.jar:$current_directory/lib/smackx-debug.jar:$current_directory/lib/smackx-jingle.jar:$current_directory:$current_directory/lib/commons-dbcp-1.2.2.jar:$current_directory/lib/commons-pool-1.5.4.jar:$current_directory/lib/commons-lang-2.4.jar


#nohup  /usr/local/java/bin/java chat.GtalkBot &

#nohup  /usr/lib/jvm/java-6-sun/bin/java chat.GtalkBot_Start &
nohup /usr/local/jdk1.6.0_14/bin/java chat.GtalkBot_Start  &

#nohup  /usr/local/java/bin/java performance.performanceStator &

#wait
#java -jar /usr/local/smack/gtalkBot.jar
