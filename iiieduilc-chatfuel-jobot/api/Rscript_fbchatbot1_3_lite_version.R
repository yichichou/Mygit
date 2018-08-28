# http://www.ppvke.com/Blog/archives/43170
#install.packages("RODBC")
#install.packages("dbConnect")
#install.packages("DBI")
#install.packages("gWidgets")
#install.packages("RMySQL")
#install.packages("xlsx")
#install.packages("rJava")
#install.packages("xlsxjars")
#install.packages("tmcn")
#install.packages("tm")
#install.packages('corpus')
#install.packages('tmap')
#install.packages("llply")
#install.packages("genbankr")
#install.packages("rscproxy")
#install.packages("wikipediatrend")
#install.packages("pullword")
#install.packages("tmcn", repos="http://R-Forge.R-project.org")

args <- commandArgs(TRUE)  #接外部值用
# 取得使用者輸入的 N 值
FB_ID <- args[1]
#FB_ID<-toString(n)
#library(rscproxy)
library(RODBC)
library(DBI)  # DBI 是 R Database Interface
library(RMySQL)  # MySQL 在 R 的 INterface

library(gWidgets)
library(dbConnect)
library(rJava)
library(xlsxjars)
library(xlsx)

library(jiebaRD)
library(jiebaR)
library(devtools)
library(rJava)

library(tmap)


library(tmcn) 

library(NLP)
library(tm)

library(gWidgets)
library(plyr)  #llply用的

# 建立資料庫連線
conn = dbConnect(MySQL(), dbname = "fb_interview",username = "FB", password = "12345678"
                 ,host = "localhost" )


dbListTables(conn)



#---------------------下sql指令↓--------------------#
#FB_ID='1692763614137588'
#選出最新user的HashCode
scode<-paste("SELECT `HashCode` FROM `fb_chatbot_result` WHERE `FB_ID`='",FB_ID,"' ORDER BY `id` DESC limit 1 ",sep = "")
Query = dbGetQuery(conn,scode)
HashCode<-Query$HashCode
#在資料庫搜尋指令


s<-paste("SELECT * FROM `fb_chatbot` WHERE `HashCode`='",HashCode,"'",sep = "")
result = dbGetQuery(conn,s)


result1<-c(do.call("cbind",result))  #convert list to character
ress<-toUTF8(result1)  #character才能使用此方法
ress_matrix<-matrix(ress,ncol=length(result)) #convert character to matrix
ress_frame<-as.data.frame(ress_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame

colnames(ress_frame)<-c("id","HashCode","Time","FB_ID","Gender","UserName","Phone","Email","FB_name","QuestionNo","Question","Answer","pos_score","neg_score","total_score","score_text")  #修改欄位名稱

stopword1 = dbGetQuery(conn ,"select * from stopwords" )

stopword2<-c(do.call("cbind",stopword1))  
stopword3<-toUTF8(stopword2)  
stopword_matrix<-matrix(stopword3,ncol=length(stopword1) )
stopword_frame<-as.data.frame(stopword_matrix ,stringsAsFactors=FALSE) 
colnames(stopword_frame)<-c("text")  #修改欄位名稱




POS1 = dbGetQuery(conn ,"select * from sentiment_pos" )
POS2<-POS1$Text
POS3<-toUTF8(POS2)  #character才能使用此方法
POS_matrix<-matrix(POS3,ncol=length(POS1) ) #convert character to matrix
POS_frame<-as.data.frame(POS_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame
colnames(POS_frame)<-c("text")  #修改欄位名稱

neg1 = dbGetQuery(conn ,"select * from sentiment_neg" )
neg2<-neg1$Text
neg3<-toUTF8(neg2)  #character才能使用此方法
neg_matrix<-matrix(neg3,ncol=length(neg1) ) #convert character to matrix
neg_frame<-as.data.frame(neg_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame
colnames(neg_frame)<-c("text")  #修改欄位名稱

POS.list <- as.list(as.data.frame(t(POS_frame)))  #conver a data frme to a list
NEG.list <- as.list(as.data.frame(t(neg_frame)))  #conver a data frme to a list
mydict <- c(POS.list,NEG.list)#兩個情緒辭典合併
engine <- worker(bylines = TRUE )
mydict1<-toString(unlist(mydict))


new_user_word(engine, mydict1)

#str(ress_frame$Answer)
#对每一條的句子進行斷詞
segwords1= NULL
segwords1 <- sapply(ress_frame$Answer, segment, engine)
#segwords1 <- sapply(ress_frame$Answer,segment)
#進行停止詞過濾
word1 <- stopword_frame[!nchar(stopword_frame)==0]

stopwords<-word1
stopwords<- unlist(stopwords)  

removeStopWords <- function(x,stopwords) {  
  temp <- character(0)  
  index <- 1  
  xLen <- length(x)  
  while (index <= xLen) {  
    if (length(stopwords[stopwords==x[index]]) <1)  
      temp<- c(temp,x[index])  
    index <- index +1  
  }  
  temp  
} 


segwords2 <-lapply(segwords1, removeStopWords, stopwords)
#自定义情感类型得分函数

fun <- function( x, y) x%in% y
getEmotionalType <- function( x,pwords,nwords){
  print("x")
  pos.weight = sapply(llply( x,fun,pwords),sum)
  
  neg.weight = sapply(llply( x,fun,nwords),sum)
  
  total = (pos.weight-neg.weight)
  
  return(data.frame( pos.weight, neg.weight, total))
  
}

score<- getEmotionalType(segwords2, POS3, neg3)  #都要為character

commentEmotionalRank <-list(score) #DATA FRAME TO LIST 

evalu.score <- transform(score,emotion = ifelse(total>= 0, 'positive', 'negative'))

#將資料傳回至DB來更新DATA

#將情感值更新至資料庫中
for (r in 1:nrow(evalu.score)) {
  
  POSscore <- evalu.score$pos.weight[r]
  
  NEGscore <- evalu.score$neg.weight[r]
  TOTALscore <- evalu.score$total[r]
  TEXTscore<-toString(evalu.score$emotion[r])
  
  UPquery<-paste("UPDATE `fb_chatbot` SET `pos_score`=",POSscore, ", `neg_score`=",NEGscore,", `total_score`=",TOTALscore,", `score_text`='",TEXTscore,"' WHERE `HashCode`='",HashCode,"' AND `score_text` = '' Order by `Time` ASC LIMIT 1",sep='')
  print(UPquery)
  Uquery1=iconv(x=UPquery,to="UTF-8")
  
  dbSendQuery(conn ,Uquery1)
}





#=========================================================================#
#計算每一題實際的得分數

s<-list()
s[1:20]<-0
s<-unlist(s, use.names=FALSE)

#ress_frame$QuestionNo[1]
for (i in 1:nrow(ress_frame)) {
  no<-ress_frame$QuestionNo[i]
  no<-as.numeric(no)
  s[no]=s[no]+evalu.score$total[i]
  
  i=i+1
}
t=0
t<-sum(s[1:length(s)]) 
if(t>=0){
  tn<-'confidence'
}else{
  tn<-'negative'
}

new_s<-list()
new_s[1:3]<-0
for(n in 1:length(s)){
  index=ress_frame$QuestionNo[n]
  index<-as.numeric(index)
  new_s[n]=s[index]
}

UP1query<-paste("UPDATE `fb_chatbot_result` SET `no1`=",new_s[1], ", `no2`=",new_s[2],", `no3`=",new_s[3],", `total_score`=",t,", `Text`='",tn,"' WHERE `HashCode`='",HashCode,"' ORDER BY id DESC limit 1",sep='')
print(UP1query)
#插入至新的結果資料表中
UP1query=iconv(x=UP1query,to="UTF-8")
dbSendQuery(conn ,UP1query)





dbDisconnect(conn)  #結束連線
