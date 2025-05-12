<?php
require_once '../../includes/config.php';
require_once '../../includes/autoload.php';

if (!isset($_SESSION['user']) || ($_SESSION['user']['role'] != 'admin' && $_SESSION['user']['role'] != 'super_admin')) {
    header("Location: login.php");
    exit();
}

$activity = new UserActivity();
$recentActivity = $activity->getRecentActivity(50);
$pageAggregates = $activity->getPageViewAggregates();
$eventAggregates = $activity->getEventTypeAggregates();
?>
<?php include '../includes/header.php'; ?>
<main>
    <h2>User Activity Dashboard</h2>
    
    <section>
        <h3>Recent Activity</h3>
        <?php if (count($recentActivity) > 0): ?>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>User</th>
                        <th>Event Type</th>
                        <th>Page URL</th>
                        <th>IP Address</th>
                        <th>Additional Data</th>
                        <th>Timestamp</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($recentActivity as $log): ?>
                        <tr>
                            <td><?php echo $log['id']; ?></td>
                            <td><?php echo htmlspecialchars($log['username'] ? $log['username'] : 'Guest'); ?></td>
                            <td><?php echo htmlspecialchars($log['event_type']); ?></td>
                            <td><?php echo htmlspecialchars($log['page_url']); ?></td>
                            <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                            <td><?php echo htmlspecialchars($log['additional_data']); ?></td>
                            <td><?php echo $log['created_at']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No recent activity logs found.</p>
        <?php endif; ?>
    </section>
    
    <section>
        <h3>Page Views Summary</h3>
        <?php if (count($pageAggregates) > 0): ?>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Page URL</th>
                        <th>Total Views</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($pageAggregates as $page): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($page['page_url']); ?></td>
                            <td><?php echo $page['total_views']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No page view data available.</p>
        <?php endif; ?>
    </section>
    
    <section>
        <h3>Event Types Summary</h3>
        <?php if (count($eventAggregates) > 0): ?>
            <table border="1" cellpadding="5">
                <thead>
                    <tr>
                        <th>Event Type</th>
                        <th>Total Count</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($eventAggregates as $evt): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($evt['event_type']); ?></td>
                            <td><?php echo $evt['total']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No event data available.</p>
        <?php endif; ?>
    </section>
</main>
<?php include '../includes/footer.php'; ?>
