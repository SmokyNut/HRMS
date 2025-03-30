import csv
import random
import datetime

# ダミーデータ用のリスト
departments = ["Sales", "Engineering", "HR", "Marketing", "Finance"]
first_names = ["Alice", "Bob", "Charlie", "David", "Emma", "Frank", "Grace", "Hannah", "Isaac", "Jack"]
last_names = ["Smith", "Johnson", "Brown", "Taylor", "Anderson", "Thomas", "Jackson", "White", "Harris", "Martin"]

# CSVファイルを作成
filename = "employees.csv"
with open(filename, mode="w", newline="", encoding="utf-8") as file:
    writer = csv.writer(file)
    
    # ヘッダー
    writer.writerow(["staff_id", "staff_name", "department", "gender", "start_date"])
    
    # 10000レコード生成
    for i in range(10000):
        staff_id = f"EMP{i+1:05d}"  # EMP00001, EMP00002 のようなID
        staff_name = f"{random.choice(first_names)} {random.choice(last_names)}"
        department = random.choice(departments)
        gender = random.randint(1, 3)
        start_date = datetime.date(2000, 1, 1) + datetime.timedelta(days=random.randint(0, 8000))  # 2000年1月1日〜最近

        writer.writerow([staff_id, staff_name, department, gender, start_date])

print(f"{filename} を作成しました！")