import mysql.connector
import base64
from difflib import SequenceMatcher
import sys
x = sys.argv[1]
conn = mysql.connector.connect(host="localhost",port="3306",user="root",password="",database="classroom")
cursor = conn.cursor()
cursor.execute("""select data,aim,subject,name from storage where id = %s""",(x,))
record1 = cursor.fetchall()
for rows in record1:
    data1 = rows[0]
    aim = rows[1]
    subject = rows[2]
    name = rows[3]
print("<h3>Selected Document Fetched: ",name)
cursor.execute("select data,name,student from storage where id <> %s and aim = %s and subject = %s",(x,aim,subject))
record2 = cursor.fetchall()
print("</h3><h3>Other Documents from the same assignment: ",cursor.rowcount)
print("</h3>")
for rows in record2:
    data2 = rows[0]
    blob1 = base64.b64decode(data1)
    blob2 = base64.b64decode(data2)
    print("<br><h3 style = 'color:green'>For file ",rows[1])
    file_1_data = blob1
    file_2_data = blob2
    similarity = SequenceMatcher(None, file_1_data,file_2_data).ratio()
    print( "</h3><p style = 'color:brown; margin:0px; padding:0px;'>Percentage Similarity: ",round(similarity*100,2),"%")
    print("</p><p>Submitted by ",rows[2],"</p>")
cursor.close()
conn.close()
