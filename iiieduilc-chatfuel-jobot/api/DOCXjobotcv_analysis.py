#pip install python-docx 處理docx
#pip install mammoth  將docx/doc轉為html
#pip install pypandoc 將word轉為html

#!/usr/bin/python
import sys

#get the arguments passed


FB_ID=''
Hashcode=''
cv_file=''



FB_ID =sys.argv[1]



import mysql.connector

conn = mysql.connector.connect(user='FB', password='12345678',
                              host='localhost',
                              database='fb_interview')

"""
FB_ID='1124567'

"""

#读取docx中的文本代码示例
import docx
#获取文档对象

#查詢前，必須先獲取游標
cur =conn.cursor ()

sql="SELECT doc_copy FROM fb_chatbot_result WHERE FB_ID='{}' Order by id DESC LIMIT 1".format(FB_ID)
#執行的都是原生SQL語句
cur.execute(sql)
cv_file=cur.fetchone()
cv_file = ''.join(cv_file)
print(cv_file)
cv_file="C:/xampp/htdocs/JoBot/api/file/{}".format(cv_file)

 
docx = docx.Document(cv_file)  
docText = '\n'.join([paragraph.text for paragraph in docx.paragraphs])  
print(docText)      


#======================
""""進行DISC分析"""
import sys,os,io



#def pdp(input_text, userid):
#pwd = sys.path[0]

# Send the Text to CKip
from PDP2 import PDP_socket
"""with io.open(outputString,'r',encoding='utf-8') as content :
    data=content.read().replace('\n', '')"""
res = PDP_socket.socket_connect(docText)

# Calculate the Score
from PDP2 import PDP_score
DISCscore = PDP_score.vocabase_score(res)

# Output the Result to File
#作業系統的路徑os.path.abspath(pwd)
f = open("C:" + "/Output_for_DISC/" + FB_ID + "_result.txt", "w")
f.write("%.2f\n" %DISCscore[0])
f.write("%.2f\n" %DISCscore[1])
f.write("%.2f\n" %DISCscore[2])
f.write("%.2f\n" %DISCscore[3])

print("Score :", DISCscore[0], DISCscore[1], DISCscore[2], DISCscore[3])
sys.stdout.flush()

"""將 DISCscore 更新至資料"""
cur =conn.cursor ()

update_sql=\
"UPDATE fb_chatbot_result SET DScore={}, IScore={}, SScore={}, CScore={} WHERE FB_ID='{}' Order by id DESC LIMIT 1".format(DISCscore[0], DISCscore[1], DISCscore[2], DISCscore[3], FB_ID)

cur.execute(update_sql)
conn.commit()  #執行sql指令

#=======================================


 #斷詞
seg_list=[]
import jieba 

seg_list = jieba.lcut(docText)  # 默认是精确模式
print(seg_list) 
    

#加载停用词表

#查詢前，必須先獲取游標
cur =conn.cursor ()

#執行的都是原生SQL語句
cur.execute ( "SELECT * FROM stopwords" )

stop_tuple=()
for  stop  in  cur.fetchall (): 
    stop_tuple=stop_tuple+(stop)
  
stop_list=list(stop_tuple)
""""
stop_words=[]
list(stop_words)   
stop_words[1]
"""    
    
# jieba.load_userdict('userdict.txt')  
# 创建停用词list  

stopwords = stop_list # 这里加载停用词的路径  
outstr ="" 
word_list=[] 
for word in seg_list:  
    if word not in stopwords:  
        if word != '\t' or word != '\n' :  
            outstr += word  
            outstr += " "
            word_list.append(word)

outstr = " ".join(outstr.split()) #將\n \t 去掉  
     


"""執行 skill"""  
#查詢前，必須先獲取游標
cur =conn.cursor ()
#執行的都是原生SQL語句
sql="SELECT Words,Score FROM cv_keywords WHERE Job='programmer' AND Class='skill' "
cur.execute(sql)
#conn.commit()  #執行sql指令

skill_score=0.0                                            
for (Words, Score) in cur:
    #print("{}, {}".format(Words, Score)) 
    for i in range(0, len(word_list)):
        if word_list[i]== Words:
            skill_score+=Score
            print("{},{}".format(word_list[i],skill_score))

skill_score=round(skill_score, 3)       
            

"""執行 experience"""  
#查詢前，必須先獲取游標
cur =conn.cursor ()
#執行的都是原生SQL語句
sql="SELECT Words,Score FROM cv_keywords WHERE Job='programmer' AND Class='experience' "
cur.execute(sql)
#conn.commit()  #執行sql指令

experience_score=0.0
for (Words, Score) in cur:
    #print("{}, {}".format(Words, Score)) 
    for i in range(0, len(word_list)):
        if word_list[i]== Words:
            experience_score+=Score
            print("{},{}".format(word_list[i],experience_score))

experience_score=round(experience_score, 3)       


"""執行 trait"""  
#查詢前，必須先獲取游標
cur =conn.cursor ()
#執行的都是原生SQL語句
sql="SELECT Words,Score FROM cv_keywords WHERE Job='programmer' AND Class='trait' "
cur.execute(sql)
#conn.commit()  #執行sql指令

trait_score=0.0
for (Words, Score) in cur:
    #print("{}, {}".format(Words, Score)) 
    for i in range(0, len(word_list)):
        if word_list[i]== Words:
            trait_score+=Score
            print("{},{}".format(word_list[i],trait_score))

trait_score=round(trait_score, 3)     



"""總分計算"""
#total=0.0
#total=skill_score+experience_score+trait_score

    

  

"""將score更新至資料"""
cur =conn.cursor ()

update_sql=\
   "UPDATE fb_chatbot_result SET skill_score={}, experience_score={}, trait_score={} WHERE FB_ID='{}' Order by id DESC LIMIT 1".format(skill_score, experience_score, trait_score, FB_ID)

cur.execute(update_sql)
conn.commit()  #執行sql指令

conn.close ()


import time
print("SUCCESS!")
time.sleep(10)


