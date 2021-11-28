# 🌎 get started

ทำการ clone project bsru-kanban-board-demo จาก github

{% embed url="https://github.com/ujiik009/bsru-kanban-board-demo" %}

1. เปลี่ยน path ไปที่ c:/

```
cd c:/
```

2\. ทำการ clone project จาก github

```
git clone https://github.com/ujiik009/bsru-kanban-board-demo.git
```

3\. ทำการ เปลี่ยน path ไปที่ bsru-kanban-board-demo

```
cd bsru-kanban-board-demo
```

4\. ทำการ install package ที่จำเป็น จากไฟล์ package.json

```
npm install
```

5\. ทำการ ติดตั้ง package axios เพื่อให่สามารถ ทำการ call API ผ่าน http ได้

```
npm install axios --save
```

6\. ทำการ open project ด้วย vs-code ผ่านตำสั่ง `code .`

```
code .
```

![](<../.gitbook/assets/image (67).png>)

7\. ทำการ สร้างไฟล์ .env

![](<../.gitbook/assets/image (129).png>)

8\. ทำการเปิดไฟล์ .env แล้วทำการ copy code ชุดด้านล่างไปแทนที่

```ini
VUE_APP_API=http://127.0.0.1
```
