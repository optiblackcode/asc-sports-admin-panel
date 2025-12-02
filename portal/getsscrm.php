<?php
include "include/common.php";
// ----------------------------------------------------
// DEBUG MODE
// ----------------------------------------------------
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ----------------------------------------------------
// DB CONNECTION
// ----------------------------------------------------
$host     = "localhost";
$username = "root";
$password = "muHVAR.7K^E?+xB;4";
$db       = "asc";
$table    = "zoho_camps";

$mysqli = new mysqli($host, $username, $password, $db);
if ($mysqli->connect_error) {
    die("DB Connection Failed: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

// Search fields allowed
$searchFields = array(
    "zoho_camp_name",
    "zoho_camp_id",
    "camp_status",
    "camp_region",
    "camp_state"
);

// ---------------------------------------------
// SEARCH HANDLING
// ---------------------------------------------
$search = isset($_GET['search']) ? trim($_GET['search']) : "";

$where = "";
$params = array();
$types  = "";

if ($search !== "") {
    $like = "%" . $search . "%";

    $whereParts = array();
    foreach ($searchFields as $field) {
        $whereParts[] = "`$field` LIKE ?";
        $params[] = $like;
        $types   .= "s";
    }
    $where = "WHERE (" . implode(" OR ", $whereParts) . ")";
}

// ---------------------------------------------
// PAGINATION
// ---------------------------------------------
$perPage = 100;
$page    = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$offset  = ($page - 1) * $perPage;

// ---------------------------------------------
// COUNT TOTAL ROWS
// ---------------------------------------------
$countSQL = "SELECT COUNT(*) AS total FROM `$table` $where";
$stmt = $mysqli->prepare($countSQL);
if ($where !== "") {
    call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), refValues($params)));
}
$stmt->execute();
$res = $stmt->get_result();
$totalRow = $res->fetch_assoc();
$total = $totalRow["total"];
$stmt->close();

$totalPages = ceil($total / $perPage);

// ---------------------------------------------
// FETCH DATA (ONLY NEEDED COLUMNS)
// ---------------------------------------------
$colList = "`rec_id`, `zoho_camp_id`, `zoho_camp_name`, `camp_status`, `camp_region`, `camp_state`, `digital_marketing_cost`";
$sql = "SELECT $colList FROM `$table` $where ORDER BY rec_id DESC LIMIT $offset, $perPage";

$stmt = $mysqli->prepare($sql);
if ($where !== "") {
    call_user_func_array(array($stmt, "bind_param"), array_merge(array($types), refValues($params)));
}
$stmt->execute();
$result = $stmt->get_result();

$rows = array();
while ($r = $result->fetch_assoc()) {
    $rows[] = $r;
}
$stmt->close();

// ---------------------------------------------
// Helper for PHP 5.6 bind_param (reference array)
// ---------------------------------------------
function refValues($arr) {
    $refs = array();
    foreach ($arr as $key => $value) {
        $refs[$key] = &$arr[$key];
    }
    return $refs;
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Zoho Camps Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">
<div class="container my-4">

<h2 class="mb-4">Zoho Camps Admin</h2>

<!-- SEARCH BOX -->
<form class="mb-4" method="get">
    <div class="input-group">
        <input type="text"
               name="search"
               class="form-control"
               placeholder="Search by Camp Name, ID, Status, Region, State..."
               value="<?php echo h($search); ?>">
        <button class="btn btn-primary">Search</button>
    </div>
</form>

<!-- DATA TABLE -->
<div class="card">
    <div class="card-header">Records</div>
    <div class="card-body p-0">
        <table class="table table-striped table-sm mb-0">
            <thead>
                <tr>
                    <th>rec_id</th>
                    <th>zoho_camp_id</th>
                    <th>zoho_camp_name</th>
                    <th>camp_status</th>
                    <th>camp_region</th>
                    <th>camp_state</th>
                    <th>digital_marketing_cost</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($rows)): ?>
                <tr><td colspan="7" class="text-center py-3 text-muted">No records found</td></tr>
                <?php else: ?>
                    <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?php echo h($r["rec_id"]); ?></td>
                        <td><?php echo h($r["zoho_camp_id"]); ?></td>
                        <td><?php echo h($r["zoho_camp_name"]); ?></td>
                        <td><?php echo h($r["camp_status"]); ?></td>
                        <td><?php echo h($r["camp_region"]); ?></td>
                        <td><?php echo h($r["camp_state"]); ?></td>
                        <td><?php echo h($r["digital_marketing_cost"]); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- PAGINATION -->
    <div class="card-footer">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a class="btn btn-sm <?php echo ($i==$page ? 'btn-success' : 'btn-outline-secondary'); ?>"
               href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
               <?php echo $i; ?>
            </a>
        <?php endfor; ?>
    </div>

</div>

</div>
</body>
</html>
