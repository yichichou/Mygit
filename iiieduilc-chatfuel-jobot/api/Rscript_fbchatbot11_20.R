
args <- commandArgs(TRUE)  #接外部值用
# 取得使用者輸入的 N 值
FB_ID <- args[1]
 
library(RODBC)
library(DBI)  # DBI 是 R Database Interface
library(RMySQL)  # MySQL 在 R 的 INterface

library(gWidgets)
library(dbConnect)
library(rJava)
library(xlsxjars)
library(xlsx)


# 建立資料庫連線
conn = dbConnect(MySQL(), dbname = "fb_interview",username = "FB", password = "12345678"
                 ,host = "localhost" )


dbListTables(conn)


#---------------------下sql指令↓--------------------#
#FB_ID='1692763614137588'
#選出最新user的HashCode
scode<-paste("SELECT `HashCode` FROM `fb_chatbot_result` WHERE `FB_ID`='",FB_ID,"' ORDER BY `id` DESC limit 1 ",sep = "")
HashCode = dbGetQuery(conn,scode)

#在資料庫搜尋指令

s<-paste("SELECT Answer FROM `fb_chatbot_1` WHERE `HashCode`='",HashCode,"'",sep = "")
result = dbGetQuery(conn,s)
typeof(result) #list
output <- matrix(unlist(result), byrow = TRUE)


no=11
type1=0
type2=0
type3=0
type4=0
for (a in output) {
  print(no)
  if (a=='1') {
    name<-'ST'
    type1=type1+1
  }
  if (a=='2') {
    name<-'SF'
    type2=type2+1
  }
  if (a=='3') {
    name<-'NF'
    type3=type3+1
  }
  if (a=='4') {
    name<-'NT'
    type4=type4+1
  }
  UPquery<-paste("UPDATE `fb_chatbot_1` SET `",name,"`='",1,"' WHERE `HashCode`='",HashCode,"' AND QuestionNo=",no,sep='')
  Uquery1=iconv(x=UPquery,to="UTF-8")
  
  dbSendQuery(conn ,Uquery1)
  no=no+1
}
#計算百分比
t1<-type1/length(output)*100
t2<-type2/length(output)*100
t3<-type3/length(output)*100
t4<-type4/length(output)*100


UPquery<-paste("UPDATE `fb_chatbot_result` SET `ST`=",t1,", `SF`=",t2,", `NF`=",t3,", `NT`=",t4," WHERE `HashCode`='",HashCode,"' ORDER BY id DESC limit 1",sep='')


Uquery2=iconv(x=UPquery,to="UTF-8")

dbSendQuery(conn ,Uquery2)

#=========================================================================#
dbDisconnect(conn)  #結束連線