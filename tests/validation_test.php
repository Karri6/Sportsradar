<?php
/**
 * Some simple validation tests
 * Run directly with: php tests/validation_test.php
 */

require_once __DIR__ . '/../www/backend/validation.php';

// Global test counters
$passed = 0;
$failed = 0;

/**
 * Assertion helpers
 */
function assertEquals($expected, $actual, $testName) {
    global $passed, $failed;

    if ($expected === $actual) {
        echo "PASS: $testName\n";
        $passed++;
    } else {
        echo "FAIL: $testName\n";
        echo "  Expected: " . var_export($expected, true) . "\n";
        echo "  Got:      " . var_export($actual, true) . "\n";
        $failed++;
    }
}

function assertTrue($condition, $testName) {
    global $passed, $failed;

    if ($condition) {
        echo "PASS: $testName\n";
        $passed++;
    } else {
        echo "FAIL: $testName\n";
        echo "  Expected: true/1\n";
        echo "  Got:      " . var_export($condition, true) . "\n";
        $failed++;
    }
}

function assertFalse($condition, $testName) {
    global $passed, $failed;

    if (!$condition) {
        echo "PASS: $testName\n";
        $passed++;
    } else {
        echo "FAIL: $testName\n";
        echo "  Expected: false/0/null\n";
        echo "  Got:      " . var_export($condition, true) . "\n";
        $failed++;
    }
}

echo "\nRunning Validation Tests\n\n";

// Test 1: sanitizeText removes whitespace
assertEquals(
    'Hello World',
    sanitizeText('  Hello World  '),
    'sanitizeText removes leading/trailing whitespace'
);

// Test 2: sanitizeText removes HTML tags
assertEquals(
    'Hello World',
    sanitizeText('<script>Hello World</script>'),
    'sanitizeText removes HTML tags'
);

// Test 3: sanitizeText limits length
$result = sanitizeText(str_repeat('a', 300), 100);
assertEquals(
    100,
    strlen($result),
    'sanitizeText limits string to max length'
);

// Test 4: validateDate accepts valid date
assertTrue(
    validateDate('2025-11-01'),
    'validateDate accepts valid date format'
);

// Test 5: validateDate rejects invalid month
assertFalse(
    validateDate('2025-13-01'),
    'validateDate rejects invalid month'
);

// Test 6: validateDate rejects wrong format
assertFalse(
    validateDate('11/01/2025'),
    'validateDate rejects wrong format'
);

// Test 7: validateTime accepts valid time
assertTrue(
    validateTime('14:30'),
    'validateTime accepts valid time format'
);

// Test 8: validateTime rejects invalid hour
assertFalse(
    validateTime('24:00'),
    'validateTime rejects hour >= 24'
);

// Test 9: validateTime rejects invalid minute
assertFalse(
    validateTime('14:60'),
    'validateTime rejects minute >= 60'
);

// Test 10: validateId accepts positive integers
assertEquals(
    5,
    validateId('5'),
    'validateId accepts positive integer'
);

// Test 11: validateId rejects zero
assertFalse(
    validateId('0'),
    'validateId rejects zero'
);

// Test 12: validateId rejects negative numbers
assertFalse(
    validateId('-1'),
    'validateId rejects negative numbers'
);

// Test 13: buildUrl creates correct URL
$url = buildUrl('sports', ['id' => 5, 'filter' => 'active']);
$expected = 'index.php?page=sports&id=5&filter=active';
assertEquals(
    $expected,
    $url,
    'buildUrl creates correct URL with parameters'
);

// Summary
echo "\nTest Summary\n";
echo "Passed: $passed\n";
echo "Failed: $failed\n";

if ($failed === 0) {
    echo "All tests passed!\n";
    exit(0);
} else {
    echo "Some tests failed.\n";
    exit(1);
}
