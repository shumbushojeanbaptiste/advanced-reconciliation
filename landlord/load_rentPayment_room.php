<?php
$cmp_ID = 9;
require_once("../include/initialize.php");

// Function to validate and sanitize input
function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Function to parse numeric input including millions
function parseNumericInput($input) {
    // Remove any non-numeric characters except digits, decimal points, and commas
    $cleaned = preg_replace('/[^0-9,.]/', '', $input);
    
    // Replace commas with nothing (in case they're used as thousand separators)
    $cleaned = str_replace(',', '', $cleaned);
    
    // Check for million notation (e.g., 2m or 2million)
    if (preg_match('/(\d+(?:\.\d+)?)\s*m(?:illion)?/i', $input, $matches)) {
        $number = floatval($matches[1]) * 1000000;
        return $number;
    }
    
    // Check for thousand notation (e.g., 2k or 2thousand)
    if (preg_match('/(\d+(?:\.\d+)?)\s*k(?:ilo)?(?:thousand)?/i', $input, $matches)) {
        $number = floatval($matches[1]) * 1000;
        return $number;
    }
    
    // Default case - regular number
    return floatval($cleaned);
}

// Check if there is a pending item with the same slip number
$slip_no1 = sanitizeInput($_POST['slip_no']);
$sqlslippending = $conn->prepare("SELECT * FROM tbl_payment WHERE status='1' AND slip_no=:slip_no");
$sqlslippending->execute([':slip_no' => $slip_no1]);
$countslippending = $sqlslippending->rowCount();
$dateinvo111=$_POST['date'];

// Parse the loan amount properly
$totalLoanAmount1 = parseNumericInput($_POST['blncename']);

if ($_POST['blncename'] >= 0) {
    if ($countslippending == 0) {
        $roomid1w1 = sanitizeInput($_POST['roomid1']);
        
        // Check if room assignments are different
        $sqlslippendingroom = $conn->prepare("SELECT pay_ref_no FROM tbl_payment WHERE status = '2'");
        $sqlslippendingroom->execute();
        $countslippendingroom = $sqlslippendingroom->rowCount();
        
        if ($countslippendingroom == 0) {
            $ten_ref = sanitizeInput($_POST['tenant_ref']);
            $bank = sanitizeInput($_POST['bank']);
            $slip_no = sanitizeInput($_POST['slip_no']);
            $payment_fees = parseNumericInput($_POST['payment_fees']);
            $payment_desc = sanitizeInput($_POST['comment']);
            $date = sanitizeInput($_POST['date']);
            $dateooo = $date;
            $dateinvo111=$date;
           // $dateinvo111=$dateooo;
            $todaysdate = date("Y-m-d H:i:s");
            $roomid1w = sanitizeInput($_POST['roomid1']);
            $checkadv = 0;
            $checknormal = 0;
            $totalLoanAmount = parseNumericInput($_POST['blncename']);

            // Check if slip number already exists
            $sqlslip = $conn->prepare("SELECT * FROM tbl_payment WHERE slip_no=:slip_no");
            $sqlslip->execute([':slip_no' => $slip_no]);
            $countslip = $sqlslip->rowCount();
            
            if ($countslip == 0) {
                
                $cash = ['payment_fees' => $payment_fees];
                $total = $payment_fees;
                $advance = max(0, $total);
                $remainingAmount = 0;
                $sqltrnas = $conn->prepare("SELECT MIN(invoice_id) as invoice_id, invoice_date, SUM(invoice_fees) as invoice_fees, inv_room_id, inv_ref_no, MAX(invoice) as invoice, MAX(serve_id) as serve_id FROM tbl_invoice WHERE inv_ref_no = :inv_ref_no GROUP BY inv_room_id, invoice_date ORDER BY serve_id ASC");
                $sqltrnas->bindParam(':inv_ref_no', $ten_ref, PDO::PARAM_STR);
                $sqltrnas->execute();

                while ($rowtrans = $sqltrnas->fetch(PDO::FETCH_ASSOC)) {
                    $transid = $rowtrans['invoice_id'];
                    $transDate = $rowtrans['invoice_date'];
                    $transamount = $rowtrans['invoice_fees'];
                    $roomid = $rowtrans['inv_room_id'];
                    $tenant = $rowtrans['inv_ref_no'];
                    $trans_code = $rowtrans['invoice'];
                    $serve_id = $rowtrans['serve_id'];
                    $amountTrans = 0;

                    $yearMonth = date("Y-m", strtotime($transDate));

                    // Get the total paid amount for each transaction
                    $sqlpaid = $conn->prepare("SELECT SUM(pay_amount) as pay_amount FROM tbl_payment 
                        WHERE pay_ref_no=:ten_ref AND invoice_date LIKE :yearMonth AND pay_room_id=:roomid AND status=1");
                    $sqlpaid->execute([
                        ':ten_ref' => $ten_ref,
                        ':yearMonth' => "$yearMonth%",
                        ':roomid' => $roomid
                    ]);
                    
                    $rowpaid = $sqlpaid->fetch();
                    $paidAmount = $rowpaid['pay_amount'] ?? 0;
                    $amountTrans += $paidAmount;

                    // Calculate remaining amount
                    $remainingAmount = $transamount - $amountTrans;

                    if ($remainingAmount > 0) {
                        $cashContributions = [];

                        // Process cash to cover remaining amount
                        foreach ($cash as $cash_id => &$cash_amount) {
                            if ($cash_amount > 0 && $remainingAmount > 0) {
                                if ($remainingAmount >= $cash_amount) {
                                    $cashContributions[] = [
                                        'mode' => $bank,
                                        'amount' => $cash_amount,
                                        'remainingAmount' => $remainingAmount,
                                        'transid' => $transid,
                                        'transDate' => $transDate,
                                        'trans_code' => $trans_code,
                                        'roomid' => $roomid,
                                        'serve_id' => $serve_id,
                                        'tenant' => $tenant
                                    ];
                                    $remainingAmount -= $cash_amount;
                                    $cash_amount = 0;
                                } else {
                                    $cashContributions[] = [
                                        'mode' => $bank,
                                        'amount' => $remainingAmount,
                                        'remainingAmount' => 0,
                                        'transid' => $transid,
                                        'transDate' => $transDate,
                                        'trans_code' => $trans_code,
                                        'roomid' => $roomid,
                                        'serve_id' => $serve_id,
                                        'tenant' => $tenant
                                    ];
                                    $cash_amount -= $remainingAmount;
                                    $remainingAmount = 0;
                                }
                            }

                            if ($remainingAmount <= 0) {
                                break;
                            }
                        }

                        // Insert payments into the database
                        foreach ($cashContributions as $contribution) {
                            $remain = $contribution['amount'];
                            $new_pay_id = $contribution['trans_code'];
                            $inv_date = $contribution['transDate'];
                            $roomid = $contribution['roomid'];
                            $ten_ref = $contribution['tenant'];
                            $converted = 1;
                            $serveid = $contribution['serve_id'];

                            if ($remain > 0) {
                                $sql = $conn->prepare("INSERT INTO tbl_payment 
                                    (trans_code, pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount, pay_description, 
                                    pay_trm, pay_user, rent_date, c_value, rec_by, pay_room_id, serve_id, invoice_date, status)
                                    VALUES 
                                    (:trans_code, :ten_ref, :bank, :slip_no, :dateooo, :remain, :payment_desc, 
                                    :todaysdate, :cmp_ID, :inv_date, :converted, :user_id, :roomid1w, :serveid, :inv_date, 2)");
                                
                                $sql->execute([
                                    ':trans_code' => $new_pay_id,
                                    ':ten_ref' => $ten_ref,
                                    ':bank' => $bank,
                                    ':slip_no' => $slip_no,
                                    ':dateooo' => $dateinvo111,
                                    ':remain' => $remain,
                                    ':payment_desc' => $payment_desc,
                                    ':todaysdate' => $todaysdate,
                                    ':cmp_ID' => $cmp_ID,
                                    ':inv_date' => $inv_date,
                                    ':converted' => $converted,
                                    ':user_id' => $_SESSION['USER_ID'],
                                    ':roomid1w' => $roomid1w,
                                    ':serveid' => $serveid,
                                    ':inv_date' => $inv_date
                                ]);
                                
                                if ($sql->rowCount() > 0) {
                                    $checknormal = 1;
                                    $advance=$advance-$remain;
                                }
                            }
                        }
                    }
                }

                // Calculate advance
                

                if ($advance > 0) {
                    // Select tenant
                    $sqlten1 = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ten_ref");
                    $sqlten1->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
                    $sqlten1->execute();
                    $rowten1 = $sqlten1->fetch();
                    $tenid = $rowten1['ten_id'];

                    // Select assigned rooms
                    $sqlten1room = $conn->prepare("SELECT * FROM tbl_assign WHERE tent_id = :tenid and assgn_status=1");
                    $sqlten1room->bindParam(':tenid', $tenid);
                    $sqlten1room->execute();

                    $rooms = [];
                    while ($rowten1room = $sqlten1room->fetch()) {
                        $amountroom = $rowten1room['amount'] - $rowten1room['discount'];
                        $rooms[] = [
                            'id' => $rowten1room['room_id'],
                            'amount' => $amountroom
                        ];
                    }

                    // Get the last invoice
                    $sqltrnas1 = $conn->prepare("SELECT * FROM tbl_invoice 
                        WHERE inv_ref_no = :inv_ref_no  ORDER BY invoice_date DESC LIMIT 1");
                    $sqltrnas1->bindParam(':inv_ref_no', $ten_ref, PDO::PARAM_STR);
                    $sqltrnas1->execute();
                    $lastinvoice = $sqltrnas1->fetch();
                    $lastmonth = $lastinvoice['invoice_date'] ?? date("Y-m-d");
                    
                    $date = new DateTime($lastmonth);
                    $date->modify('+1 month');

                    $month = $date->format('m');
                    $year = $date->format('Y');
                    $payments = [];

                    do {
                        $initialAdvance = $advance;
                        foreach ($rooms as $room) {
                            $roomId = $room['id'];
                            $price = $room['amount'];
                            
                            if ($advance >= $price) {
                                $payments[] = [
                                    'room' => $roomId,
                                    'amount' => $price,
                                    'month' => $month,
                                    'year' => $year
                                ];
                                $advance -= $price;
                            } elseif ($advance > 0) {
                                $payments[] = [
                                    'room' => $roomId,
                                    'amount' => $advance,
                                    'month' => $month,
                                    'year' => $year
                                ];
                                $advance = 0;
                            }
                        }

                        if ($advance === $initialAdvance) {
                            break;
                        }

                        $date->modify('+1 month');
                        $month = $date->format('m');
                        $year = $date->format('Y');
                    } while ($advance > 0);

                    // Save payments to the database
                    foreach ($payments as $payment) {
                        $roomid1 = $payment['room'];
                        $serveid1 = 4;
                        $roomAmount = $payment['amount'];
                        $dateinvo = $payment['year'] . '-' . $payment['month'] . '-01 00:00:00';
                        $dateinvo1 = $payment['year'] . '-' . $payment['month'] . '-01';

                        // Generate invoice code
                        $year1 = date('Y');
                        $year = substr($year1, -2);
                        $li_qu = $db->prepare("SELECT * FROM tbl_invoice WHERE inv_ref_no=:ten_ref ORDER BY invoice_id DESC LIMIT 1");
                        $li_qu->execute([':ten_ref' => $ten_ref]);
                        $fli = $li_qu->fetch();
                        $li = str_pad($fli['invoice_id'] ?? 0, 6, "0", STR_PAD_LEFT);
                        $invoice = $year . '-' . $li;
                          
                        if ($roomAmount > 0) {
                            $sql1 = $conn->prepare("INSERT INTO tbl_payment 
                                (trans_code, pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount, pay_description, 
                                pay_trm, pay_user, rent_date, c_value, rec_by, pay_room_id, serve_id, adv_sts, invoice_date, status)
                                VALUES 
                                (:invoice, :ten_ref, :bank, :slip_no, :dateooo, :roomAmount, :payment_desc, 
                                :todaysdate, :cmp_ID, :dateinvo, :converted, :user_id, :roomid1, :serveid1, 1, :dateinvo1, 2)");
                            
                            $sql1->execute([
                                ':invoice' => $invoice,
                                ':ten_ref' => $ten_ref,
                                ':bank' => $bank,
                                ':slip_no' => $slip_no,
                                ':dateooo' => $dateinvo111,
                                ':roomAmount' => $roomAmount,
                                ':payment_desc' => $payment_desc,
                                ':todaysdate' => $todaysdate,
                                ':cmp_ID' => $cmp_ID,
                                ':dateinvo' => $dateinvo,
                                ':converted' => 1,
                                ':user_id' => $_SESSION['USER_ID'],
                                ':roomid1' => $roomid1,
                                ':serveid1' => $serveid1,
                                ':dateinvo1' => $dateinvo1
                            ]);
                            
                            if ($sql1->rowCount() > 0) {
                                $checkadv = 1;
                            }
                        }
                    }
                }

                if ($checkadv == 1 || $checknormal == 1) {
                    echo "Done";
                }
            } else {
                echo "Exist";
            }
        } else {
            echo "same room not";
        }
    } else {
        echo "pending";
    }
} else {
    // Handle advance payment case
    if ($countslippending == 0) {
        $roomid1w1 = sanitizeInput($_POST['roomid1']);
        
        // Check if room assignments are different
        $sqlslippendingroom = $conn->prepare("SELECT pay_ref_no FROM tbl_payment WHERE status = '2'");
        $sqlslippendingroom->execute();
        $countslippendingroom = $sqlslippendingroom->rowCount();
        
        if ($countslippendingroom == 0) {
            $payment_id = sanitizeInput($_POST['pay_id']);
            $ten_ref = sanitizeInput($_POST['tenant_ref']);
            $bank = sanitizeInput($_POST['bank']);
            $slip_no = sanitizeInput($_POST['slip_no']);
            $payment_fees = parseNumericInput($_POST['payment_fees']);
            $payment_desc = sanitizeInput($_POST['comment']);
            $date = sanitizeInput($_POST['date']);
            $dateinvo111 = $date;
            $todaysdate = date("Y-m-d H:i:s");
            $roomid1w = sanitizeInput($_POST['roomid1']);
            $converted = 1;
            $checkadv1 = 0;
            $checkadv2 = 0;

            // Select last payment date
            $sqlpytinvo = $conn->prepare("SELECT invoice_date FROM tbl_payment 
                WHERE pay_ref_no = :ten_ref ORDER BY invoice_date and status=1 DESC LIMIT 1");
            $sqlpytinvo->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
            $sqlpytinvo->execute();
            $rowpytinvo = $sqlpytinvo->fetch();
            
            $monthlast = $rowpytinvo['invoice_date'] ?? date("Y-m-d");
            $date = new DateTime($monthlast);
            $monthpyt = $date->format("Y-m");

            // Select tenant
            $sqlten1 = $conn->prepare("SELECT ten_id FROM tbl_tenants WHERE ten_ref_no = :ten_ref");
            $sqlten1->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
            $sqlten1->execute();
            $rowten1 = $sqlten1->fetch();
            $tenid = $rowten1['ten_id'] ?? null;

            if (!$tenid) {
                die("Error: Tenant not found.");
            }

            // Select assigned rooms
            $sqlten1room = $conn->prepare("SELECT room_id, amount FROM tbl_assign 
                WHERE tent_id = :tenid and assgn_status=1 ORDER BY room_id ASC");
            $sqlten1room->bindParam(':tenid', $tenid, PDO::PARAM_INT);
            $sqlten1room->execute();

            $remainingPayment = $payment_fees;
            $paymentDetails = [];
            $remainingAmounts = [];

            while ($rowten1room = $sqlten1room->fetch()) {
                $roomid = $rowten1room['room_id'];
                $roomidprice = $rowten1room['amount'];

                // Check total paid per room
                $sqlpaid = $conn->prepare("SELECT SUM(pay_amount) as pay_amount FROM tbl_payment 
                    WHERE pay_ref_no = :ten_ref AND invoice_date LIKE :monthpyt AND status = 1");
                $likeMonth = "$monthpyt%";
                $sqlpaid->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
                $sqlpaid->bindParam(':monthpyt', $likeMonth, PDO::PARAM_STR);
                $sqlpaid->execute();
                $rowpaid = $sqlpaid->fetch();
                $paidamount = $rowpaid['pay_amount'] ?? 0;

                $remainingAmountForRoom = $roomidprice - $paidamount;

                if ($remainingAmountForRoom > 0) {
                    $allocatedAmount = min($remainingAmountForRoom, $remainingPayment);
                    $remainingPayment -= $allocatedAmount;

                    $paymentDetails[] = [
                        'roomid' => $roomid,
                        'tenant' => $ten_ref,
                        'roomTotal' => $roomidprice,
                        'previouslyPaid' => $paidamount,
                        'invoice_date' => $monthlast,
                        'addedNow' => $allocatedAmount,
                        'totalPaidNow' => $paidamount + $allocatedAmount,
                        'remainingToBePaid' => max(0, $remainingAmountForRoom - $allocatedAmount)
                    ];

                    $remainingAmounts[$roomid] = max(0, $remainingAmountForRoom - $allocatedAmount);
                }

                if ($remainingPayment <= 0) {
                    break;
                }
            }

            // Process payment allocation
            foreach ($paymentDetails as $detail) {
                $roomamount = $detail['addedNow'];
                $roomidd = $detail['roomid'];
                $invoice_date = $detail['invoice_date'];
                $serveid1 = 4;
                
                if ($roomamount > 0) {
                    $sql1 = $conn->prepare("INSERT INTO tbl_payment 
                        (pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount, pay_description, 
                        pay_trm, pay_user, rent_date, c_value, rec_by, pay_room_id, serve_id, adv_sts, invoice_date, status)
                        VALUES 
                        (:ten_ref, :bank, :slip_no, :dateinvo, :roomamount, :payment_desc, 
                        :todaysdate, :cmp_ID, :invoice_date, :converted, :user_id, :roomid1w, :serveid1, 1, :invoice_date, 2)");
                    
                    $sql1->execute([
                        ':ten_ref' => $ten_ref,
                        ':bank' => $bank,
                        ':slip_no' => $slip_no,
                        ':dateinvo' => $dateinvo111,
                        ':roomamount' => $roomamount,
                        ':payment_desc' => $payment_desc,
                        ':todaysdate' => $todaysdate,
                        ':cmp_ID' => $cmp_ID,
                        ':invoice_date' => $invoice_date,
                        ':converted' => $converted,
                        ':user_id' => $_SESSION['USER_ID'],
                        ':roomid1w' => $roomid1w,
                        ':serveid1' => $serveid1,
                        ':invoice_date' => $invoice_date
                    ]);
                    
                    if ($sql1->rowCount() > 0) {
                        $checkadv2 = 1;
                    }
                }
            }

            $amountremAll = 0;

            // Handle excess payment
            if ($remainingPayment > 0) {
                $advance2 = $remainingPayment;

                if ($advance2 > 0) {
                    // Select tenant
                    $sqlten1 = $conn->prepare("SELECT * FROM tbl_tenants WHERE ten_ref_no = :ten_ref");
                    $sqlten1->bindParam(':ten_ref', $ten_ref, PDO::PARAM_STR);
                    $sqlten1->execute();
                    $rowten1 = $sqlten1->fetch();
                    $tenid = $rowten1['ten_id'];

                    // Select assigned rooms
                    $sqlten1room = $conn->prepare("SELECT * FROM tbl_assign WHERE tent_id = :tenid and assgn_status=1");
                    $sqlten1room->bindParam(':tenid', $tenid);
                    $sqlten1room->execute();

                    $rooms = [];
                    while ($rowten1room = $sqlten1room->fetch()) {
                        $amountroom = $rowten1room['amount'] - $rowten1room['discount'];
                        $rooms[] = [
                            'id' => $rowten1room['room_id'],
                            'amount' => $amountroom
                        ];
                    }

                    // Get the last invoice
                    $lastmonth = $monthpyt . '-01';
                    $date = new DateTime($lastmonth);
                    $date->modify('+1 month');

                    $month = $date->format('m');
                    $year = $date->format('Y');
                    $payments = [];

                    do {
                        $initialAdvance2 = $advance2;
                        foreach ($rooms as $room) {
                            $roomId = $room['id'];
                            $price = $room['amount'];
                            
                            if ($advance2 >= $price) {
                                $payments[] = [
                                    'room' => $roomId,
                                    'amount' => $price,
                                    'month' => $month,
                                    'year' => $year
                                ];
                                $advance2 -= $price;
                            } elseif ($advance2 > 0) {
                                $payments[] = [
                                    'room' => $roomId,
                                    'amount' => $advance2,
                                    'month' => $month,
                                    'year' => $year
                                ];
                                $advance2 = 0;
                            }
                        }

                        if ($advance2 === $initialAdvance2) {
                            break;
                        }

                        $date->modify('+1 month');
                        $month = $date->format('m');
                        $year = $date->format('Y');
                    } while ($advance2 > 0);

                    // Save payments to the database
                    foreach ($payments as $payment) {
                        $roomid1 = $payment['room'];
                        $serveid1 = 4;
                        $roomAmount = $payment['amount'];
                        $dateinvo = $payment['year'] . '-' . $payment['month'] . '-01 00:00:00';
                        $dateinvo1 = $payment['year'] . '-' . $payment['month'] . '-01';

                        // Generate invoice code
                        $year1 = date('Y');
                        $year = substr($year1, -2);
                        $li_qu = $db->prepare("SELECT * FROM tbl_invoice WHERE cpny_ID=:cmp_ID ORDER BY invoice_id DESC LIMIT 1");
                        $li_qu->execute([':cmp_ID' => $cmp_ID]);
                        $fli = $li_qu->fetch();
                        $li = str_pad($fli['invoice_id'] ?? 0, 6, "0", STR_PAD_LEFT);
                        $invoice = $year . '-' . $li;

                        if ($roomAmount > 0) {
                            $sql1 = $conn->prepare("INSERT INTO tbl_payment 
                                (pay_ref_no, bank_id, slip_no, pay_due_date, pay_amount, pay_description, 
                                pay_trm, pay_user, rent_date, c_value, rec_by, pay_room_id, serve_id, adv_sts, invoice_date, status)
                                VALUES 
                                (:ten_ref, :bank, :slip_no, :dateinvo, :roomAmount, :payment_desc, 
                                :todaysdate, :cmp_ID, :dateinvo, :converted, :user_id, :roomid1w, :serveid1, 1, :dateinvo1, 2)");
                            
                            $sql1->execute([
                                ':ten_ref' => $ten_ref,
                                ':bank' => $bank,
                                ':slip_no' => $slip_no,
                                ':dateinvo' => $dateinvo111,
                                ':roomAmount' => $roomAmount,
                                ':payment_desc' => $payment_desc,
                                ':todaysdate' => $todaysdate,
                                ':cmp_ID' => $cmp_ID,
                                ':dateinvo' => $dateinvo,
                                ':converted' => $converted,
                                ':user_id' => $_SESSION['USER_ID'],
                                ':roomid1w' => $roomid1w,
                                ':serveid1' => $serveid1,
                                ':dateinvo1' => $dateinvo1
                            ]);
                            
                            if ($sql1->rowCount() > 0) {
                                $checkadv1 = 1;
                            }
                        }
                    }
                }
            }

            if ($checkadv1 == 1 || $checkadv2 == 1) {
                echo "Done";
            }
        } else {
            echo "same room not";
        }
    } else {
        echo "pending";
    }
}

?>