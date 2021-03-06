
#資料庫連線

# install.packages("RTextTools")
# install.packages("SparseM")
# install.packages("base")
# install.packages("pdftools")
# install.packages("magrittr")

args <- commandArgs(TRUE)  #接外部值用
# 取得使用者輸入的 N 值
FB_ID <- args[1]

library(RODBC)
library(DBI)  # DBI 是 R Database Interface
library(RMySQL)  # MySQL 在 R 的 INterface
library(magrittr)
library(gWidgets)
library(dbConnect)
library(rJava)
library(xlsxjars)
library(xlsx)
library(NLP)
library(tm)  #數據預處理
#library(textcat) #數據預處理:文本的語言進行分類
#install.packages("RTextTools")
#library(RTextTools) #數據預處理:對文本進行探勘操作
#-----------------------進行斷詞↓----------------------------------#
library(jiebaRD)
library(jiebaR)
library(devtools)

library(corpus)
library(tmap)

library(NLP)
library(tmcn)

#讀取pdf文本，

library(pdftools)  #pdf_text方法用
library(magrittr) # %>%用

# 建立資料庫連線
conn = dbConnect(MySQL(), dbname = "fb_interview",username = "FB", password = "12345678"
                 ,host = "localhost" )


dbListTables(conn)

#=====================================================================

# FB_ID='1659469274101742'

#DocAll='C:\\Users\\Administrator\\Downloads\\2009wuming-chanandersonresumev5-091125104507-phpapp01.pdf'
#FB_ID='1692763614137588'
#DocAll='C:\\Users\\Administrator\\Downloads\\創新研發提案團隊同仁面試.pdf'



DocAll<-paste("SELECT doc_copy FROM fb_chatbot_result  WHERE `FB_ID` ='",FB_ID,"' ORDER BY `id` DESC limit 1", sep="")
DocAll = dbGetQuery(conn ,DocAll)
typeof(DocAll)  #list
DocAll<-paste( unlist(DocAll), collapse='')  #list to string
DocAll<-paste("file\\",DocAll,sep="")
#DocAll<-paste("C:\\xampp\\htdocs\\Report\\chatbot\\file\\2018-04-24-13-50-02.pdf",sep="")

info_text<-pdf_text(DocAll)
info_text = gsub("\r", " ", info_text)
info_text = gsub("\n", " ", info_text)
typeof(as.list(info_text))
info_text<-as.list(info_text)


#建置文本的語料庫
info_text1<-Corpus(VectorSource(info_text))



#==================在資料庫讀取停止詞的table=========================================
#在資料庫讀取停止詞的table
#在資料庫搜尋指令
stopword1 = dbGetQuery(conn ,"select * from stopwords" )

stopword2<-c(do.call("cbind",stopword1))  #convert list to character

stopword3<- toUTF8(stopword2)  #character才能使用此方法


stopword_matrix<-matrix(stopword3,ncol=length(stopword1)) #convert character to matrix


stopword_frame<-as.data.frame(stopword_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame
head(stopword_frame)
colnames(stopword_frame)<-c("text")  #修改欄位名稱


#-------------------利用停止詞進行斷詞/並進行過濾↓↓↓-------------------------------------------

engine <- worker(bylines = TRUE )


# 將正負面詞加入自定義的辭典裡
#new_user_word(engine)
str(info_text)
# 對每一條句子進行斷詞
segment_words1 <- sapply(info_text, segment, engine)

#進行停止詞過濾
word1 <- stopword_frame[!nchar(stopword_frame)==0]

stop_words<-word1
stop_words<- unlist(stop_words)  

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



segment_words2 <-lapply(segment_words1, removeStopWords, stop_words)

#-----------------利用停止詞進行斷詞/並進行過濾↑↑↑----------------------

#========================讀取工程師的關鍵字詞=============================================
#讀取工程師的「技能」skill關鍵字詞
skill_word<-paste("SELECT `Words`,`Score` FROM `cv_keywords`  WHERE `Class`='Skill'",sep="")
skill_word = dbGetQuery(conn ,skill_word)

skill_word1<-c(do.call("cbind",skill_word))  #convert list to character

skill_word2<-toUTF8(skill_word1) 
skillword_matrix<-matrix(skill_word2,ncol=length(skill_word)) #convert character to matrix

skillword_frame<-as.data.frame(skillword_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame
colnames(skillword_frame)<-c("Words","Score")  #修改欄位名稱
dim(skillword_frame)


#讀取工程師的「學經歷」experience 關鍵字詞
experience_word<-paste("SELECT `Words`,`Score` FROM `cv_keywords`  WHERE `Class`='experience'",sep="")
experience_word = dbGetQuery(conn ,experience_word)

experience_word1<-c(do.call("cbind",experience_word))  #convert list to character

experience_word2<-toUTF8(experience_word1) 
experienceword_matrix<-matrix(experience_word2,ncol=length(experience_word)) #convert character to matrix

experienceword_frame<-as.data.frame(experienceword_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame
colnames(experienceword_frame)<-c("Words","Score")  #修改欄位名稱
dim(experienceword_frame)


#讀取工程師的「人格特質」trait 關鍵字詞
trait_word<-paste("SELECT `Words`,`Score` FROM `cv_keywords`  WHERE `Class`='trait'",sep="")
trait_word = dbGetQuery(conn ,trait_word)

trait_word1<-c(do.call("cbind",trait_word))  #convert list to character

trait_word2<-toUTF8(trait_word1) 
traitword_matrix<-matrix(trait_word2,ncol=length(trait_word)) #convert character to matrix

traitword_frame<-as.data.frame(traitword_matrix ,stringsAsFactors=FALSE) #convert matrix to data.frame
colnames(traitword_frame)<-c("Words","Score")  #修改欄位名稱
dim(traitword_frame)



#-------------------進行關鍵字分數評分↓↓↓--------------------------------------
#自定义情感类型得分函数
#library(gWidgets)
library(plyr)  #llply用的
#「技能」skill關鍵字詞評分

info_words<-unlist(segment_words2) #list to character
typeof(info_words)

skill_score<-0
for (n in 1:length(info_words)) {
  for (i in 1:nrow(skillword_frame)) {
    if (info_words[n]==skillword_frame[i,1]) {
      score<-as.numeric(skillword_frame[i,2])
      print(score)
      skill_score=skill_score+score
    }
   
  }
}
skill_score  #總分數



#「學經歷」experience關鍵字詞評分



experience_score<-0
for (n in 1:length(info_words)) {
  for (i in 1:nrow(experienceword_frame)) {
    if (info_words[n]==experienceword_frame[i,1]) {
      score<-as.numeric(experienceword_frame[i,2])
      print(score)
      experience_score=experience_score+score
    }
    
  }
}
experience_score  #總分數



#「人格特質」trait 關鍵字詞評分



trait_score<-0
for (n in 1:length(info_words)) {
  for (i in 1:nrow(traitword_frame)) {
    if (info_words[n]==traitword_frame[i,1]) {
      score<-as.numeric(traitword_frame[i,2])
      print(score)
      trait_score=trait_score+score
    }
    
  }
}
trait_score  #總分數

#===================將各關鍵字的字詞分數更新置資料表中=============================================

#將資料傳回至DB來更新DATA

#將字詞分數更新至資料庫中

UPquery<-paste("UPDATE `fb_chatbot_result` SET `skill_score`=",skill_score, ", `experience_score`=",experience_score,", `trait_score`=",trait_score," WHERE `FB_ID`='",FB_ID,"' Order by `id` DESC LIMIT 1",sep='')
Uquery1=iconv(x=UPquery,to="UTF-8")
dbSendQuery(conn ,Uquery1)



#=========================================================================#


dbDisconnect(conn)  #結束連線




