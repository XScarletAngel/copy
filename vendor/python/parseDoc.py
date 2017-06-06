#coding=utf-8
#usage python <script_name> <docFilePath>
#pip install python-docx [安装一下扩展库]
import sys
import os

from docx import Document

#获取当前脚本得名称
argv0_list = sys.argv[0].split("\\");
script_name = argv0_list[len(argv0_list) - 1]; 
usage = "\n Usage python <"+script_name+"> <docFilePath>"

if len(sys.argv) != 2:
	print "Warning:\n docx file is empty" + usage
	sys.exit()
docx_path = sys.argv[1]
if not os.path.exists(docx_path):
	print "Warning:\n docx file is not exist" + usage
        sys.exit()

#打开文档
document = Document(docx_path)
#读取每段资料
l = [ paragraph.text.encode('utf8') for paragraph in document.paragraphs];
#输出并观察结果，也可以通过其他手段处理文本即可
for i in l:
    print i
#读取表格材料，并输出结果
tables = [table for table in document.tables];
for table in tables:
    for row in table.rows:
        for cell in row.cells:
            print cell.text.encode('utf8'),'\t',
