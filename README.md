# Web-Hoc-tu-vung-tieng-anh-
# 📚 LexiLoop
> Learn Smarter. Remember Longer.

LexiLoop là website hỗ trợ học từ vựng tiếng Anh bằng phương pháp **Spaced Repetition (SRS)**, giúp người học ghi nhớ từ vựng lâu hơn thông qua việc ôn tập đúng thời điểm.

---

# 📖 Giới thiệu

Việc học từ vựng theo cách truyền thống thường khiến người học nhanh quên sau một thời gian ngắn. LexiLoop được xây dựng nhằm giải quyết vấn đề đó bằng cách:

- Quản lý bộ từ vựng cá nhân.
- Học từ mới bằng Flashcard.
- Ôn tập theo phương pháp Spaced Repetition.
- Kiểm tra mức độ ghi nhớ thông qua Quiz.
- Theo dõi tiến độ học tập bằng Dashboard.

---

# 🎯 Mục tiêu của dự án

- Xây dựng một hệ thống học từ vựng trực quan.
- Áp dụng phương pháp Spaced Repetition vào quá trình ôn tập.
- Giúp người dùng học đúng thời điểm để tăng khả năng ghi nhớ.
- Theo dõi tiến độ học tập thông qua thống kê và biểu đồ.

---

# ✨ Chức năng chính

## 👤 Người dùng

### 1. Đăng ký & Đăng nhập

- Đăng ký tài khoản
- Đăng nhập
- Đăng xuất
- Cập nhật thông tin cá nhân

---

### 2. Quản lý bộ từ vựng

Người dùng có thể:

- Thêm từ mới
- Chỉnh sửa từ
- Xóa từ
- Phân loại theo chủ đề

Thông tin mỗi từ gồm:

- Word
- Meaning
- IPA
- Example
- Note

---

### 3. Flashcard

- Hiển thị từ tiếng Anh
- Lật thẻ để xem nghĩa
- Xem ví dụ
- Xem phiên âm

---

### 4. Ôn tập theo Spaced Repetition

Hệ thống sẽ:

- Tự động xác định từ cần ôn trong ngày.
- Hiển thị bài Quiz.
- Dựa vào kết quả đúng/sai để cập nhật ngày ôn tiếp theo.

Ví dụ:

Đúng

↓

Ôn lại sau **3 ngày**

Sai

↓

Ôn lại sau **1 ngày**

---

### 5. Quiz

Các dạng bài:

- Trắc nghiệm
- Điền từ (nếu triển khai)

Sau khi hoàn thành Quiz:

- Chấm điểm
- Cập nhật kết quả
- Điều chỉnh lịch ôn

---

### 6. Dashboard

Hiển thị:

- Tổng số từ đã học
- Số từ cần ôn hôm nay
- Độ chính xác
- Biểu đồ tiến độ học

---

### 7. Lịch ôn tập

Website tự động tạo lịch ôn dựa trên kết quả học.

Người dùng **không cần tự tạo lịch học**.

Website chỉ hiển thị:

- Hôm nay cần ôn bao nhiêu từ
- Danh sách từ cần ôn

---

## 👨‍💻 Quản trị viên

- Quản lý người dùng
- Quản lý chủ đề
- Quản lý bộ từ mặc định
- Xem thống kê hệ thống

---

# 🔄 Quy trình hoạt động

```text
Đăng nhập

↓

Tạo bộ từ

↓

Học Flashcard

↓

Làm Quiz

↓

Hệ thống đánh giá

↓

Cập nhật ngày ôn

↓

Dashboard

↓

Tiếp tục học
```

---

# 🗄️ Cơ sở dữ liệu

| Table | Mô tả |
|---------|----------------------------|
| Users | Thông tin người dùng |
| Topics | Chủ đề từ vựng |
| Vocabulary | Danh sách từ vựng |
| UserVocabulary | Tiến độ học tập của từng người dùng |
| QuizHistory | Lịch sử làm Quiz |
| Roles | Phân quyền người dùng |

---

# 💻 Công nghệ sử dụng

## Frontend

- HTML5
- CSS3
- Bootstrap 5
- JavaScript

## Backend

- ASP.NET Core MVC

## Database

- SQL Server

## Thư viện

- Entity Framework Core
- Chart.js

---

# 📂 Cấu trúc thư mục

```
LexiLoop
│
├── Controllers
├── Models
├── Views
├── Services
├── Data
├── wwwroot
│   ├── css
│   ├── js
│   ├── images
│
├── README.md
└── Program.cs
```

---

# 🚀 Hướng phát triển

Trong tương lai, hệ thống có thể mở rộng thêm:

- AI tạo ví dụ cho từ vựng.
- Đồng bộ dữ liệu nhiều thiết bị.
- Học bằng giọng nói.
- Gamification (Huy hiệu, điểm thưởng).
- Chia sẻ bộ từ giữa người dùng.

---

# 👥 Thành viên nhóm

| Họ và tên | MSSV | Vai trò |
|-----------|------|----------|
| Nguyễn Văn A | 215xxxx | Backend |
| Trần Văn B | 215xxxx | Frontend |
| Lê Văn C | 215xxxx | Database |
| ... | ... | ... |

---

# 📅 Tiến độ

- [x] Phân tích yêu cầu
- [ ] Thiết kế cơ sở dữ liệu
- [ ] Thiết kế giao diện
- [ ] Phát triển Backend
- [ ] Phát triển Frontend
- [ ] Kiểm thử
- [ ] Hoàn thiện báo cáo

---

# 📄 Giấy phép

Dự án được phát triển phục vụ mục đích học tập và nghiên cứu tại trường đại học.

---

# ❤️ Cảm ơn

Cảm ơn bạn đã quan tâm đến dự án **LexiLoop**.

Nếu có góp ý hoặc phát hiện lỗi, vui lòng tạo **Issue** hoặc **Pull Request** để cùng cải thiện dự án.
