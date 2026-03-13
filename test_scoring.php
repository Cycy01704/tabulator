<?php
// Mocking the behavior for verification

$scores = collect([
    (object)['judge_id' => 1, 'score' => 40],
    (object)['judge_id' => 1, 'score' => 60],
    (object)['judge_id' => 2, 'score' => 30],
    (object)['judge_id' => 2, 'score' => 45],
]);

$total = $scores->sum('score');
echo "Total Sum: " . $total . "\n";

$count = $scores->count();
echo "Total Records: " . $count . "\n";

$oldAvg = $count > 0 ? $total / $count : 0;
echo "Old Avg (per item): " . $oldAvg . "\n";

$judgeCount = $scores->pluck('judge_id')->unique()->count();
echo "Judge Count: " . $judgeCount . "\n";

$newAvg = $judgeCount > 0 ? $total / $judgeCount : 0;
echo "New Avg (per judge): " . $newAvg . "\n";

if ($newAvg == 87.5) {
    echo "Logic verification PASSED (for 2 judges with sum 100 and 75)\n";
} else {
    echo "Logic verification FAILED\n";
}
