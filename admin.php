<?php
session_start();

// Initialize data if not exists
if (!isset($_SESSION['users'])) {
    $_SESSION['users'] = [
        [
            'id' => 1,
            'name' => 'John Doe',
            'email' => 'john.doe@email.com',
            'member_since' => 'Jan 2024',
            'swaps' => 15,
            'points' => 320,
            'status' => 'active'
        ],
        [
            'id' => 2,
            'name' => 'Sarah Anderson',
            'email' => 'sarah.anderson@email.com',
            'member_since' => 'Mar 2024',
            'swaps' => 8,
            'points' => 180,
            'status' => 'active'
        ],
        [
            'id' => 3,
            'name' => 'Mike Johnson',
            'email' => 'mike.johnson@email.com',
            'member_since' => 'Feb 2024',
            'swaps' => 0,
            'points' => 0,
            'status' => 'blocked',
            'spam_reports' => 3
        ],
        [
            'id' => 4,
            'name' => 'Emma Wilson',
            'email' => 'emma.wilson@email.com',
            'member_since' => 'Today',
            'swaps' => 0,
            'points' => 0,
            'status' => 'active'
        ]
    ];
}

if (!isset($_SESSION['orders'])) {
    $_SESSION['orders'] = [
        [
            'id' => 12345,
            'buyer' => 'John Doe',
            'seller' => 'Sarah Anderson',
            'item1' => 'Vintage Dress',
            'item2' => 'Designer Jacket',
            'created' => '2 hours ago',
            'status' => 'pending'
        ],
        [
            'id' => 12344,
            'buyer' => 'Emma Wilson',
            'seller' => 'Mike Johnson',
            'item1' => 'Sneakers',
            'item2' => 'Casual Shirt',
            'created' => '1 day ago',
            'status' => 'completed'
        ],
        [
            'id' => 12343,
            'buyer' => 'Lisa Brown',
            'seller' => 'Tom Davis',
            'item1' => 'Formal Suit',
            'item2' => 'Evening Dress',
            'created' => '3 days ago',
            'status' => 'disputed',
            'dispute_reason' => 'Item not as described'
        ]
    ];
}

if (!isset($_SESSION['listings'])) {
    $_SESSION['listings'] = [
        [
            'id' => 1,
            'title' => 'Vintage Summer Dress',
            'seller' => 'Sarah Anderson',
            'size' => 'M',
            'condition' => 'Excellent',
            'listed' => '3 hours ago',
            'status' => 'pending',
            'emoji' => '👗'
        ],
        [
            'id' => 2,
            'title' => 'Designer Leather Jacket',
            'seller' => 'John Doe',
            'size' => 'L',
            'condition' => 'Good',
            'listed' => '1 day ago',
            'status' => 'approved',
            'emoji' => '🧥'
        ],
        [
            'id' => 3,
            'title' => 'Inappropriate Content Title',
            'seller' => 'Spam User',
            'reports' => 5,
            'reason' => 'Inappropriate content',
            'listed' => '2 days ago',
            'status' => 'flagged',
            'emoji' => '👔'
        ],
        [
            'id' => 4,
            'title' => 'White Running Shoes',
            'seller' => 'Emma Wilson',
            'size' => '8',
            'condition' => 'Like New',
            'listed' => '5 hours ago',
            'status' => 'pending',
            'emoji' => '👟'
        ]
    ];
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $type = $_POST['type'] ?? '';
    $id = intval($_POST['id'] ?? 0);
    
    switch ($type) {
        case 'users':
            handleUserAction($action, $id);
            break;
        case 'orders':
            handleOrderAction($action, $id);
            break;
        case 'listings':
            handleListingAction($action, $id);
            break;
    }
    
    // Redirect to prevent form resubmission
    header("Location: " . $_SERVER['PHP_SELF'] . "?tab=" . $type);
    exit;
}

function handleUserAction($action, $id) {
    if (!isset($_SESSION['users'])) return;
    
    foreach ($_SESSION['users'] as &$user) {
        if ($user['id'] == $id) {
            switch ($action) {
                case 'block':
                    $user['status'] = 'blocked';
                    break;
                case 'unblock':
                    $user['status'] = 'active';
                    break;
            }
            break;
        }
    }
}

function handleOrderAction($action, $id) {
    if (!isset($_SESSION['orders'])) return;
    
    foreach ($_SESSION['orders'] as &$order) {
        if ($order['id'] == $id) {
            switch ($action) {
                case 'approve':
                    $order['status'] = 'approved';
                    break;
                case 'reject':
                    $order['status'] = 'rejected';
                    break;
            }
            break;
        }
    }
}

function handleListingAction($action, $id) {
    if (!isset($_SESSION['listings'])) return;
    
    foreach ($_SESSION['listings'] as $key => &$listing) {
        if ($listing['id'] == $id) {
            switch ($action) {
                case 'approve':
                    $listing['status'] = 'approved';
                    break;
                case 'reject':
                    $listing['status'] = 'rejected';
                    break;
                case 'remove':
                    unset($_SESSION['listings'][$key]);
                    break;
            }
            break;
        }
    }
}

// Get current tab
$currentTab = $_GET['tab'] ?? 'users';

// Filter and search functions
function filterData($data, $search = '', $filter = '') {
    if (empty($search) && empty($filter)) {
        return $data;
    }
    
    return array_filter($data, function($item) use ($search, $filter) {
        $matchesSearch = true;
        $matchesFilter = true;
        
        if (!empty($search)) {
            $searchFields = [];
            if (isset($item['name'])) $searchFields[] = $item['name'];
            if (isset($item['email'])) $searchFields[] = $item['email'];
            if (isset($item['title'])) $searchFields[] = $item['title'];
            if (isset($item['seller'])) $searchFields[] = $item['seller'];
            if (isset($item['buyer'])) $searchFields[] = $item['buyer'];
            
            $matchesSearch = false;
            foreach ($searchFields as $field) {
                if (stripos($field, $search) !== false) {
                    $matchesSearch = true;
                    break;
                }
            }
        }
        
        if (!empty($filter)) {
            $matchesFilter = (isset($item['status']) && $item['status'] === $filter);
        }
        
        return $matchesSearch && $matchesFilter;
    });
}

// Get filtered data
$search = $_GET['search'] ?? '';
$filter = $_GET['filter'] ?? '';

$filteredUsers = filterData($_SESSION['users'], $search, $filter);
$filteredOrders = filterData($_SESSION['orders'], $search, $filter);
$filteredListings = filterData($_SESSION['listings'], $search, $filter);

// Calculate stats
$totalUsers = count($_SESSION['users']);
$blockedUsers = count(array_filter($_SESSION['users'], fn($u) => $u['status'] === 'blocked'));
$newUsers = count(array_filter($_SESSION['users'], fn($u) => $u['member_since'] === 'Today'));

$totalOrders = count($_SESSION['orders']);
$pendingOrders = count(array_filter($_SESSION['orders'], fn($o) => $o['status'] === 'pending'));
$disputedOrders = count(array_filter($_SESSION['orders'], fn($o) => $o['status'] === 'disputed'));

$totalListings = count($_SESSION['listings']);
$pendingListings = count(array_filter($_SESSION['listings'], fn($l) => $l['status'] === 'pending'));
$flaggedListings = count(array_filter($_SESSION['listings'], fn($l) => $l['status'] === 'flagged'));

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - ReWear</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo">ReWear</div>
            <div class="admin-title">Admin Panel</div>
            <div class="admin-user">
                <div class="admin-avatar">A</div>
                <span>Admin</span>
            </div>
        </div>
    </header>

    <!-- Navigation Tabs -->
    <nav class="nav-tabs">
        <div class="nav-tabs-container">
            <a href="?tab=users" class="nav-tab <?= $currentTab === 'users' ? 'active' : '' ?>">
                Manage Users
            </a>
            <a href="?tab=orders" class="nav-tab <?= $currentTab === 'orders' ? 'active' : '' ?>">
                Manage Orders
            </a>
            <a href="?tab=listings" class="nav-tab <?= $currentTab === 'listings' ? 'active' : '' ?>">
                Manage Listings
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Manage Users Tab -->
        <div id="users" class="tab-content <?= $currentTab === 'users' ? 'active' : '' ?>">
            <div class="section-header">
                <h1 class="section-title">Manage Users</h1>
                <div class="section-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= $totalUsers ?></div>
                        <div class="stat-label">Total Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $newUsers ?></div>
                        <div class="stat-label">New Today</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $blockedUsers ?></div>
                        <div class="stat-label">Blocked</div>
                    </div>
                </div>
            </div>

            <form method="GET" class="controls">
                <input type="hidden" name="tab" value="users">
                <input type="text" name="search" class="search-input" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
                <select name="filter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="active" <?= $filter === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="blocked" <?= $filter === 'blocked' ? 'selected' : '' ?>>Blocked</option>
                </select>
                <button type="submit" class="filter-btn">Filter</button>
            </form>

            <div class="management-grid">
                <?php foreach ($filteredUsers as $user): ?>
                <div class="management-card">
                    <div class="item-avatar">
                        <?= strtoupper(substr($user['name'], 0, 1) . substr(explode(' ', $user['name'])[1] ?? '', 0, 1)) ?>
                    </div>
                    <div class="item-details">
                        <div class="item-title"><?= htmlspecialchars($user['name']) ?></div>
                        <div class="item-subtitle"><?= htmlspecialchars($user['email']) ?></div>
                        <div class="item-meta">
                            <span class="meta-item">Member since: <?= htmlspecialchars($user['member_since']) ?></span>
                            <span class="meta-item"><?= $user['swaps'] ?> Swaps</span>
                            <span class="meta-item"><?= $user['points'] ?> Points</span>
                            <?php if (isset($user['spam_reports'])): ?>
                                <span class="meta-item">Spam Reports: <?= $user['spam_reports'] ?></span>
                            <?php endif; ?>
                            <span class="item-status status-<?= $user['status'] ?>"><?= ucfirst($user['status']) ?></span>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button type="button" class="action-btn btn-view" onclick="alert('View Profile for <?= htmlspecialchars($user['name']) ?>')">View Profile</button>
                        <?php if ($user['status'] === 'active'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="block">
                                <input type="hidden" name="type" value="users">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="action-btn btn-block" onclick="return confirm('Block user <?= htmlspecialchars($user['name']) ?>?')">Block User</button>
                            </form>
                        <?php else: ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="unblock">
                                <input type="hidden" name="type" value="users">
                                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                <button type="submit" class="action-btn btn-approve" onclick="return confirm('Unblock user <?= htmlspecialchars($user['name']) ?>?')">Unblock</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Manage Orders Tab -->
        <div id="orders" class="tab-content <?= $currentTab === 'orders' ? 'active' : '' ?>">
            <div class="section-header">
                <h1 class="section-title">Manage Orders</h1>
                <div class="section-stats">
                    <div class="stat-item">
                        <div class="stat-number"><?= $totalOrders ?></div>
                        <div class="stat-label">Total Orders</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $pendingOrders ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number"><?= $disputedOrders ?></div>
                        <div class="stat-label">Disputes</div>
                    </div>
                </div>
            </div>

            <form method="GET" class="controls">
                <input type="hidden" name="tab" value="orders">
                <input type="text" name="search" class="search-input" placeholder="Search orders..." value="<?= htmlspecialchars($search) ?>">
                <select name="filter" class="filter-select">
                    <option value="">All Status</option>
                    <option value="pending" <?= $filter === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $filter === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="disputed" <?= $filter === 'disputed' ? 'selected' : '' ?>>Disputed</option>
                </select>
                <button type="submit" class="filter-btn">Filter</button>
            </form>

            <div class="management-grid">
                <?php foreach ($filteredOrders as $order): ?>
                <div class="management-card">
                    <div class="item-image">
                        <?php
                        $emoji = '📦';
                        if ($order['status'] === 'completed') $emoji = '✅';
                        if ($order['status'] === 'disputed') $emoji = '⚠️';
                        echo $emoji;
                        ?>
                    </div>
                    <div class="item-details">
                        <div class="item-title">Order #<?= $order['id'] ?></div>
                        <div class="item-subtitle"><?= htmlspecialchars($order['buyer']) ?> ↔ <?= htmlspecialchars($order['seller']) ?></div>
                        <div class="item-meta">
                            <span class="meta-item"><?= htmlspecialchars($order['item1']) ?> ↔ <?= htmlspecialchars($order['item2']) ?></span>
                            <span class="meta-item">Created: <?= htmlspecialchars($order['created']) ?></span>
                            <?php if (isset($order['dispute_reason'])): ?>
                                <span class="meta-item">Dispute: <?= htmlspecialchars($order['dispute_reason']) ?></span>
                            <?php endif; ?>
                            <span class="item-status status-<?= $order['status'] ?>"><?= ucfirst($order['status']) ?></span>
                        </div>
                    </div>
                    <div class="item-actions">
                        <button type="button" class="action-btn btn-view" onclick="alert('View details for Order #<?= $order['id'] ?>')">View Details</button>
                        <?php if ($order['status'] === 'pending'): ?>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="approve">
                                <input type="hidden" name="type" value="orders">
                                <input type="hidden" name="id" value="<?= $order['id'] ?>">
                                <button type="submit" class="action-btn btn-approve" onclick="return confirm('Approve Order #<?= $order['id'] ?>?')">Approve</button>
                            </form>
                        <?php elseif ($order['status'] === 'disputed'): ?>
                            <button type="button" class="action-btn btn-edit" onclick="alert('Resolve dispute for Order #<?= $order['id'] ?>')">Resolve</button>
                        <?php else: ?>
                            <button type="button" class="action-btn btn-edit" onclick="alert('Edit Order #<?= $order['id'] ?>')">Edit</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>