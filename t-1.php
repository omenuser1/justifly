<?php
// بيانات وهمية للطالب
$user = [
    'full_name' => 'أحمد محمد',
    'email' => 'ahmed@example.com'
];

// بيانات وهمية للتبريرات
$absences = [
    [
        'absence_id' => 1,
        'absence_date' => '2025-08-20',
        'reason' => 'مرض مع تقرير طبي',
        'status' => 'approved',
        'admin_comment' => 'تم قبول التبرير بناءً على التقرير الطبي'
    ],
    [
        'absence_id' => 2,
        'absence_date' => '2025-08-22',
        'reason' => 'ظروف عائلية',
        'status' => 'pending',
        'admin_comment' => ''
    ],
    [
        'absence_id' => 3,
        'absence_date' => '2025-08-25',
        'reason' => 'خطأ في الحضور',
        'status' => 'rejected',
        'admin_comment' => 'التبرير غير كافٍ'
    ]
];

// بيانات وهمية للإشعارات
$notifications = [
    [
        'message' => 'تم قبول تبرير الغياب بتاريخ 2025-08-20',
        'is_read' => true,
        'created_at' => '2025-08-21 10:00:00'
    ],
    [
        'message' => 'تم رفض تبرير الغياب بتاريخ 2025-08-25',
        'is_read' => false,
        'created_at' => '2025-08-26 14:00:00'
    ]
];

// بيانات وهمية للتقويم
$calendar_events = [
    [
        'title' => 'غياب مقبول',
        'start' => '2025-08-20',
        'color' => '#28a745'
    ],
    [
        'title' => 'غياب معلق',
        'start' => '2025-08-22',
        'color' => '#ffc107'
    ],
    [
        'title' => 'غياب مرفوض',
        'start' => '2025-08-25',
        'color' => '#dc3545'
    ]
];
$events_json = json_encode($calendar_events);
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة تحكم الطالب - Justifly</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        h2, h3 { color: #343a40; }
        .table th { background-color: #e9ecef; }
        #calendar { max-width: 900px; margin: 20px auto; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <!-- ملخص الطالب -->
        <h2>مرحبًا، <?php echo htmlspecialchars($user['full_name']); ?></h2>
        <p>البريد الإلكتروني: <?php echo htmlspecialchars($user['email']); ?></p>

        <!-- قسم التبريرات -->
        <h3>التبريرات المقدمة</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>رقم التبرير</th>
                    <th>تاريخ الغياب</th>
                    <th>السبب</th>
                    <th>الحالة</th>
                    <th>تعليق الأدمن</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($absences as $absence): ?>
                    <tr>
                        <td><?php echo $absence['absence_id']; ?></td>
                        <td><?php echo $absence['absence_date']; ?></td>
                        <td><?php echo htmlspecialchars($absence['reason']); ?></td>
                        <td>
                            <?php
                            $status = $absence['status'];
                            if ($status == 'pending') echo 'معلق';
                            elseif ($status == 'approved') echo 'مقبول';
                            else echo 'مرفوض';
                            ?>
                        </td>
                        <td><?php echo $absence['admin_comment'] ? htmlspecialchars($absence['admin_comment']) : '-'; ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($absences)): ?>
                    <tr><td colspan="5" class="text-center">لا توجد تبريرات مقدمة</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- قسم الإشعارات -->
        <h3>الإشعارات</h3>
        <ul class="list-group">
            <?php foreach ($notifications as $notification): ?>
                <li class="list-group-item <?php echo $notification['is_read'] ? '' : 'list-group-item-warning'; ?>">
                    <?php echo htmlspecialchars($notification['message']); ?>
                    <small class="text-muted"><?php echo $notification['created_at']; ?></small>
                </li>
            <?php endforeach; ?>
            <?php if (empty($notifications)): ?>
                <li class="list-group-item text-center">لا توجد إشعارات</li>
            <?php endif; ?>
        </ul>

        <!-- قسم التقويم -->
        <h3>التقويم الدراسي</h3>
        <div id="calendar"></div>

        <!-- زر تقديم تبرير جديد -->
        <a href="submit_absence.php" class="btn btn-primary mt-3">تقديم تبرير جديد</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'ar',
                events: <?php echo $events_json; ?>,
                height: 'auto'
            });
            calendar.render();
        });
    </script>
</body>
</html>