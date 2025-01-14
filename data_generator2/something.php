<?php
// Assume $conn is a global variable that holds the database connection

$supplier_names = [
    "ABC Supplies", "XYZ Traders", "MNO Distribution", "PQR Goods", "LMN Wholesale",
    "OPQ Suppliers", "UVW Materials", "RST Services", "JKL Enterprises", "DEF Solutions",
    "GHJ Distributors", "KLM Ventures", "NOP Inc.", "QRS Ltd.", "TUV Corp",
    "WXY Enterprises", "ZAB Merchants", "BCD Supply Co.", "EFG Trading", "HIJ Industries",
    "JKL Logistics", "MNP Corp", "OPQ Supplies", "RST Traders", "UVW Goods",
    "XYZ Wholesalers", "YZA Holdings", "BCD Importers"
];

function generateRandomName($length = 8) {
    $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function generateRandomEmail($name) {
    $domains = ["example.com", "supplier.com", "mail.com"];
    $domain = $domains[array_rand($domains)];
    return strtolower($name) . '@' . $domain;
}

function generateRandomPhoneNumber() {
    return '09' . rand(100000000, 999999999);
}

function generateRandomAddress() {
    return '1234 ' . generateRandomName(5) . ' Street, City, Country';
}

$created_on = '2020-01-01';

for ($i = 0; $i < 28; $i++) {
    $supplier_name = $supplier_names[$i % count($supplier_names)];
    $contact_person = generateRandomName();
    $email = generateRandomEmail($contact_person);
    $phone_number = generateRandomPhoneNumber();
    $address = generateRandomAddress();

    $sql = "INSERT INTO suppliers (supplier_name, contact_person, email, phone_number, address, created_on)
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssss", $supplier_name, $contact_person, $email, $phone_number, $address, $created_on);

    if ($stmt->execute()) {
        echo "Supplier $supplier_name added successfully.<br>";
    } else {
        echo "Error adding supplier $supplier_name: " . $stmt->error . "<br>";
    }

    $stmt->close();
}
?>
