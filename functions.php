<?php 
//this is transactions for normal book balance and pending
    $stmt_t = $con -> prepare('SELECT * FROM transactions WHERE user_id = ?');
    $stmt_t -> bind_param('s', $user_id);
    $stmt_t -> execute(); 
    $stmt_t -> store_result(); 
    $stmt_t -> bind_result($t_id, $transaction_id, $user_id, $type, $amount, $status, $start_date, $details, $proof, $account_paid_into, $investment_id, $investment_type, $plan_name, $level, $roi, $duration, $end_date); 
    $numrows_t = $stmt_t -> num_rows();
    if($numrows_t > 0){
        while ($stmt_t -> fetch()) { 
            if($status == "Approved"){
                if($type == "Credit"){
                    $book_balance += $amount;
                }
                if($details == "Investment"){
                    $no_plans ++;
                    //calculate investment amount to add to book balance
                    if($investment_type == "Running"){
                        $result = calculateCurrentInvestmentTotal($amount, $roi, $start_date, $duration);
                        if($result['simple_status'] == "Matured"){
                            $book_balance += $result['total'];
                        }
                        else{
                            $pending_balance += $result['total'];
                            $book_balance -= $amount;
                        }
                    }

                    if ($investment_type == "Fixed") {
                        $total_roi1 = $amount * ($roi / 100);
                        $final_amount = $amount + $total_roi1;

                        $today_dt = new DateTime($today);
                        $end_dt = new DateTime($end_date);

                        if ($end_dt <= $today_dt) {
                            $book_balance += $final_amount;
                        } else {
                            $pending_balance += $amount;
                            $book_balance -= $amount;
                        }
                    }


                }
                if($type == "Debit"){
                    $book_balance -= $amount;
                }
            }
            if($status == "Pending"){
                if($type == "Debit"){
                    $pending_balance += $amount;
                    $book_balance -= $amount;
                }
            }
        }
    }
?>