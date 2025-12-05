<?php
if ( ! class_exists( 'FPDF' ) ) {
    require_once LP_BULK_EXPORT_PLUGIN_DIR . 'includes/fpdf/fpdf.php';
}

class LP_PDF_Report extends FPDF {
    
    function Error($msg) {
        throw new Exception( 'FPDF Error: ' . $msg );
    }

    function Header() {
        // Logo
        $logo_id = get_theme_mod( 'custom_logo' );
        if ( $logo_id ) {
            $logo_path = get_attached_file( $logo_id );
            if ( file_exists( $logo_path ) ) {
                // FPDF only supports JPG, PNG, GIF
                $ext = strtolower( pathinfo( $logo_path, PATHINFO_EXTENSION ) );
                if ( in_array( $ext, array( 'jpg', 'jpeg', 'png', 'gif' ) ) ) {
                    $this->Image( $logo_path, 10, 8, 0, 12 );
                }
            }
        }
        
        // Title (Right aligned or Centered?)
        // Let's align Title Right for "Dashboard" feel if Logo is left
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(44, 62, 80); // Midnight Blue
        $this->Cell(0, 10, 'Sharky Academy Status Report', 0, 1, 'R');
        $this->SetFont('Arial', '', 10);
        $this->SetTextColor(127, 140, 141); // Gray
        $this->Cell(0, 5, 'Generated on ' . date('F j, Y'), 0, 1, 'R');
        $this->Ln(10);
    }
    
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', 'I', 8);
        $this->SetTextColor(189, 195, 199);
        $this->Cell(0, 10, 'Sharky Academy Confidential - Page ' . $this->PageNo(), 0, 0, 'C');
    }
    
    function RoundedRect($x, $y, $w, $h, $r, $style = '') {
        $k = $this->k;
        $hp = $this->h;
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='B';
        else
            $op='S';
        $MyArc = 4/3 * (sqrt(2) - 1);
        $this->_out(sprintf('%.2F %.2F m',($x+$r)*$k,($hp-$y)*$k ));
        $xc = $x+$w-$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l', $xc*$k,($hp-$y)*$k ));
        $this->_Arc($xc + $r*$MyArc, $yc - $r, $xc + $r, $yc - $r*$MyArc, $xc + $r, $yc);
        $xc = $x+$w-$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',($x+$w)*$k,($hp-$yc)*$k));
        $this->_Arc($xc + $r, $yc + $r*$MyArc, $xc + $r*$MyArc, $yc + $r, $xc, $yc + $r);
        $xc = $x+$r;
        $yc = $y+$h-$r;
        $this->_out(sprintf('%.2F %.2F l',$xc*$k,($hp-($y+$h))*$k));
        $this->_Arc($xc - $r*$MyArc, $yc + $r, $xc - $r, $yc + $r*$MyArc, $xc - $r, $yc);
        $xc = $x+$r;
        $yc = $y+$r;
        $this->_out(sprintf('%.2F %.2F l',($x)*$k,($hp-$yc)*$k));
        $this->_Arc($xc - $r, $yc - $r*$MyArc, $xc - $r*$MyArc, $yc - $r, $xc, $yc);
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c', $x1*$this->k, ($h-$y1)*$this->k,
            $x2*$this->k, ($h-$y2)*$this->k, $x3*$this->k, ($h-$y3)*$this->k));
    }
    
    function DrawDonut($x, $y, $r, $percent, $r_color=52, $g_color=152, $b_color=219) {
        $this->SetLineWidth(0.5);
        // Back Circle
        $this->SetDrawColor(236, 240, 241); // Light Gray
        $this->SetFillColor(236, 240, 241);
        $this->Circle($x, $y, $r, 'F');
        
        // Inner Circle (White) to make it a donut
        $inner_r = $r * 0.7;
        
        // Sector (Progress)
        // Start at -90 deg (12 o'clock)
        if ( $percent > 0 ) {
            $this->SetFillColor($r_color, $g_color, $b_color);
            // Draw Minor Arc (Progress Slice)
            // Logic derived for Clockwise visual starting at 12 o'clock (Top)
            // Sector draws CCW. Interval [180-P, 180] maps to [Top-P, Top] visually
            $this->Sector($x, $y, $r, 180 - ($percent/100)*360, 180, 'F', false);
        }
        
        // Hole
        $this->SetFillColor(255, 255, 255);
        $this->Circle($x, $y, $inner_r, 'F');
        
        // Text in center
        $this->SetXY($x - $r, $y - 3);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(50, 50, 50);
        $this->Cell($r*2, 6, round($percent).'%', 0, 0, 'C');
    }
    
    function Circle($x, $y, $r, $style='D') {
        $this->Ellipse($x, $y, $r, $r, $style);
    }
    
    function Ellipse($x, $y, $rx, $ry, $style='D') {
        if($style=='F') $op='f';
        elseif($style=='FD' || $style=='DF') $op='B';
        else $op='S';
        $lx=4/3*(M_SQRT2-1)*$rx;
        $ly=4/3*(M_SQRT2-1)*$ry;
        $k=$this->k;
        $h=$this->h;
        $this->_out(sprintf('%.2F %.2F m',($x+$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$rx)*$k,($h-$y-$ly)*$k,($x+$lx)*$k,($h-$y-$ry)*$k,$x*$k,($h-$y-$ry)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$lx)*$k,($h-$y-$ry)*$k,($x-$rx)*$k,($h-$y-$ly)*$k,($x-$rx)*$k,($h-$y)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x-$rx)*$k,($h-$y+$ly)*$k,($x-$lx)*$k,($h-$y+$ry)*$k,$x*$k,($h-$y+$ry)*$k));
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            ($x+$lx)*$k,($h-$y+$ry)*$k,($x+$rx)*$k,($h-$y+$ly)*$k,($x+$rx)*$k,($h-$y)*$k));
        $this->_out($op);
    }
    
    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90) {
        $d0 = $a - $o;
        $d1 = $b - $o;
        if($cw){
            $d = $d0; $d0 = $d1; $d1 = $d;
        }
        $a = ($d0 * M_PI) / 180;
        $b = ($d1 * M_PI) / 180;
        if($a >= $b) $b += 2*M_PI;
        $dx = $r * cos($a);
        $dy = $r * sin($a);
        
        $k = $this->k;
        $hp = $this->h;
        $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-($yc))*$k));
        $this->_out(sprintf('%.2F %.2F l',($xc+$dx)*$k,($hp-($yc+$dy))*$k));
        
        $d = $b - $a;
        if($d == 0) return;
        
        $angle = $d;
        
        $step = 5 * M_PI / 180; // 5 deg
        for ($i = $a; $i < $b; $i += $step) {
             $this->_out(sprintf('%.2F %.2F l', ($xc + $r * cos($i))*$k, ($hp - ($yc + $r * sin($i)))*$k));
        }
        $this->_out(sprintf('%.2F %.2F l', ($xc + $r * cos($b))*$k, ($hp - ($yc + $r * sin($b)))*$k));
        
        $this->_out(sprintf('%.2F %.2F l',($xc)*$k,($hp-($yc))*$k));
        $this->_out($style=='F'?'f':'b');
    }
}

class LP_Bulk_Export_Ajax {
    public function search_users() {
        check_ajax_referer( 'lpbe_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $term = isset( $_POST['term'] ) ? sanitize_text_field( $_POST['term'] ) : '';

        if ( empty( $term ) ) {
            wp_send_json_error( 'Term is required' );
        }

        $users = get_users( array(
            'search'         => '*' . $term . '*',
            'search_columns' => array( 'user_login', 'user_email', 'display_name' ),
            'number'         => 20,
            'fields'         => array( 'ID', 'display_name', 'user_email' ),
        ) );

        wp_send_json_success( $users );
    }

    public function get_user_courses() {
        check_ajax_referer( 'lpbe_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;

        if ( ! $user_id ) {
            wp_send_json_error( 'User ID is required' );
        }

        global $wpdb;
        $table = $wpdb->prefix . 'learnpress_user_items';
        
        // Direct DB query is safer to avoid version mismatches with LP classes
        $query = $wpdb->prepare( "
            SELECT item_id, status, graduation 
            FROM $table 
            WHERE user_id = %d 
            AND item_type = %s
        ", $user_id, 'lp_course' );
        
        $results = $wpdb->get_results( $query );
        
        if ( ! $results ) {
             // Fallback or empty
             $results = array();
        }
        
        foreach ( $results as $row ) {
            $course = get_post( $row->item_id );
            if ( $course ) {
                $courses_data[] = array(
                    'id' => $row->item_id,
                    'title' => $course->post_title,
                    'status' => $row->status,
                    'graduation' => $row->graduation
                );
            }
        }

        wp_send_json_success( $courses_data );
    }

    public function generate_pdf() {
        check_ajax_referer( 'lpbe_nonce', 'nonce' );

        if ( ! current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Unauthorized' );
        }

        $data = isset( $_POST['data'] ) ? $_POST['data'] : array();

        if ( empty( $data ) ) {
            wp_send_json_error( 'No data provided' );
        }

        // Catch FPDF errors
        try {
            // Initialize PDF using Custom Dashboard Class
            $pdf = new LP_PDF_Report( 'P', 'mm', 'A4' );
            $pdf->SetAutoPageBreak( true, 20 ); // Auto break at bottom
            $pdf->AliasNbPages();
    
            foreach ( $data as $user_entry ) {
                $user_id = intval( $user_entry['user_id'] );
                $course_ids = isset( $user_entry['courses'] ) ? $user_entry['courses'] : array();
                
                $user = get_userdata( $user_id );
                if ( ! $user ) continue;
    
                $pdf->AddPage();
                
                // --- User Card Section ---
                $pdf->SetDrawColor(220, 220, 220);
                
                // Card Shadow
                $pdf->SetFillColor(241, 241, 241);
                $pdf->RoundedRect(12, 47, 186, 30, 3, 'F'); 
                // Card Body (White)
                $pdf->SetFillColor(255, 255, 255);
                $pdf->RoundedRect(10, 45, 186, 30, 3, 'FD');
                
                // Student Info (Left)
                $pdf->SetXY(15, 50);
                $pdf->SetFont( 'Arial', 'B', 11 );
                $pdf->SetTextColor( 149, 165, 166 ); // Concrete Gray
                $pdf->Cell( 20, 6, 'STUDENT', 0, 1 );
                
                $pdf->SetX(15);
                $pdf->SetFont( 'Arial', 'B', 15 );
                $pdf->SetTextColor( 44, 62, 80 ); // Dark Blue
                $user_display = iconv( 'UTF-8', 'ISO-8859-1//TRANSLIT', $user->display_name );
                $pdf->Cell( 90, 8, $user_display, 0, 0 );
                
                // Contact Info (Right)
                $pdf->SetXY(105, 50);
                $pdf->SetFont( 'Arial', 'B', 11 );
                $pdf->SetTextColor( 149, 165, 166 );
                $pdf->Cell( 20, 6, 'CONTACT', 0, 1 );
                
                $pdf->SetX(105);
                $pdf->SetFont( 'Arial', '', 13 );
                $pdf->SetTextColor( 52, 73, 94 );
                $pdf->Cell( 90, 8, $user->user_email, 0, 0 );
                
                // Spacer
                $pdf->SetY(85); 
    
                // Courses Header
                $pdf->SetFont( 'Arial', 'B', 14 );
                $pdf->SetTextColor( 44, 62, 80 );
                $pdf->Cell( 0, 10, 'Course Progress Overview', 0, 1 );
                $pdf->Ln( 2 );
    
                foreach ( $course_ids as $course_id ) {
                    $course_id = intval( $course_id );
                    $course = get_post( $course_id );
                    if ( ! $course ) continue;
    
                    $status = 'N/A';
                    $result = 0;
                    
                    // Try LearnPress API
                    if ( function_exists( 'learn_press_get_user' ) ) {
                        try {
                            $lp_user = learn_press_get_user( $user_id );
                            if ( $lp_user && method_exists( $lp_user, 'get_course_data' ) ) {
                                $course_data = $lp_user->get_course_data( $course_id );
                                $course_object = learn_press_get_course( $course_id );

                                if ( $course_data ) {
                                    // User explicitly wants PROGRESS (Completed Items / Total Items), not Grade/Result.
                                    
                                    // 1. Get Total Items
                                    $total_items = 0;
                                    if ( $course_object ) {
                                        $total_items = $course_object->count_items();
                                    }
                                    
                                    // 2. Get Completed Items
                                    $completed_count = 0;
                                    // Try getting completed items (can be array or int depending on LP version)
                                    $completed_items_raw = $course_data->get_completed_items(); 
                                    
                                    if ( is_array( $completed_items_raw ) ) {
                                        $completed_count = count( $completed_items_raw );
                                    } elseif ( is_numeric( $completed_items_raw ) ) {
                                        $completed_count = intval( $completed_items_raw );
                                    }
                                    
                                    // Calculate Percentage
                                    if ( $total_items > 0 ) {
                                        $result = ( $completed_count / $total_items ) * 100;
                                    } else {
                                        // Fallback if total items 0 or failed to get
                                        if ( method_exists( $course_data, 'get_percent_completed' ) ) {
                                            $result = $course_data->get_percent_completed();
                                        } else {
                                            $result = 0;
                                        }
                                    }
                                    
                                    $status = $course_data->get_status();
                                }
                            }
                        } catch ( Exception $e ) { 
                            error_log( 'LP Bulk Export Error: ' . $e->getMessage() );
                        }
                    }
    
                    // Fallback to DB
                    if ( $status === 'N/A' ) {
                         global $wpdb;
                         $table = $wpdb->prefix . 'learnpress_user_items';
                         $q = $wpdb->prepare( "SELECT status, graduation FROM $table WHERE user_id = %d AND item_id = %d", $user_id, $course_id );
                         $row = $wpdb->get_row( $q );
                         if ( $row ) {
                             $status = $row->status;
                             $result = 0; 
                         }
                    }
                    
                    // Fix percentage value
                    if ( is_array( $result ) && isset( $result['result'] ) ) {
                        $result = $result['result'];
                    } elseif ( is_array( $result ) ) {
                        $result = isset( $result[0] ) ? $result[0] : 0;
                    }
                    $result = floatval( $result );
                    
                    // Render Course Card
                    $card_h = 35; // Height of card
                    $y = $pdf->GetY();
                    
                    // Page break check (approx card height + margin)
                    if ( $y + $card_h > 270 ) {
                        $pdf->AddPage();
                        $pdf->SetY(40); // Reset Y below header
                        $y = $pdf->GetY();
                    }
                    
                    // Course Card Shadow
                    $pdf->SetFillColor(245, 245, 245);
                    $pdf->RoundedRect(12, $y + 2, 186, $card_h, 3, 'F'); 
                    
                    // Course Card Body (White)
                    $pdf->SetFillColor(255, 255, 255);
                    $pdf->SetDrawColor(230, 230, 230);
                    $pdf->RoundedRect(10, $y, 186, $card_h, 3, 'FD');
                    
                    // --- Chart Section (Right) ---
                    // Draw Donut Chart
                    $pdf->DrawDonut(178, $y + ($card_h/2), 12, $result);
                    
                    // --- Info Section (Left) ---
                    $pdf->SetXY(15, $y + 5);
                    
                    // Title
                    $pdf->SetFont( 'Arial', 'B', 13 );
                    $pdf->SetTextColor( 44, 62, 80 );
                    $course_title = iconv( 'UTF-8', 'ISO-8859-1//TRANSLIT', $course->post_title );
                    if ( strlen( $course_title ) > 50 ) $course_title = substr( $course_title, 0, 47 ) . '...';
                    $pdf->Cell( 0, 8, $course_title, 0, 1 );
                    
                    // Dates Section
                    $date_format = get_option( 'date_format' );
                    $start_time = $course_data->get_start_time();
                    $end_time   = $course_data->get_end_time();
                    
                    $pdf->SetFont( 'Arial', '', 9 );
                    $pdf->SetTextColor( 127, 140, 141 ); // Gray
                    
                    $date_text = '';
                    if ( $start_time ) {
                        $date_text .= 'Enrolled: ' . date_i18n( $date_format, strtotime( $start_time ) );
                    }
                    if ( $end_time ) {
                        $date_text .= '  |  Finished: ' . date_i18n( $date_format, strtotime( $end_time ) );
                    }
                    
                    if ( ! empty( $date_text ) ) {
                        $pdf->SetX( 55 );
                        $pdf->Cell( 0, 5, $date_text, 0, 1 );
                    } else {
                         //$pdf->Ln( 5 ); // Space if no dates
                    }

                    // Status Pill
                    $status_txt = ucfirst( $status );
                    $color_r = 149; $color_g = 165; $color_b = 166; // Gray
                    if ( $status == 'finished' || $status == 'completed' ) { $color_r = 39; $color_g = 174; $color_b = 96; } // Green
                    elseif ( $status == 'enrolled' ) { $color_r = 41; $color_g = 128; $color_b = 185; } // Blue
                    
                    // Status Pill Background
                    $pdf->SetFillColor( $color_r, $color_g, $color_b );
                    $pdf->SetTextColor( 255, 255, 255 );
                    $pdf->SetFont( 'Arial', 'B', 8 );
                    // Position relative to card (Adjusted for Dates)
                    $pdf->RoundedRect(15, $y + 22, 28, 6, 2, 'F');
                    $pdf->SetXY(15, $y + 22);
                    $pdf->Cell( 28, 6, $status_txt, 0, 0, 'C' );
                    
                    // Lesson Count Text (Above Bar)
                    $pdf->SetXY( 55, $y + 18 );
                    $pdf->SetFont( 'Arial', '', 8 );
                    $pdf->SetTextColor( 100, 100, 100 );
                    $pdf->Cell( 110, 4, $completed_count . ' / ' . $total_items . ' items completed', 0, 0, 'R' );
                    
                    // Elegant Linear Bar (Middle)
                    // Position start at x=55 to let Status fit, width=100
                    $bar_x = 55;
                    $bar_y = $y + 23;
                    $bar_w = 110;
                    $bar_h = 4;
                    
                    // Track
                    $pdf->SetFillColor( 236, 240, 241 ); 
                    $pdf->RoundedRect($bar_x, $bar_y, $bar_w, $bar_h, 2, 'F');
                    
                    // Fill
                    if ( $result > 0 ) {
                       $fill_w = ($result / 100) * $bar_w;
                       if ( $fill_w > $bar_w ) $fill_w = $bar_w;
                       $pdf->SetFillColor( $color_r, $color_g, $color_b ); // Match status color
                       $pdf->RoundedRect($bar_x, $bar_y, $fill_w, $bar_h, 2, 'F');
                    }
                    
                    // Move cursor for next card
                    $pdf->SetY( $y + $card_h + 5 ); 
                }
            }
    
            $pdf_content = $pdf->Output( 'S' );
            $pdf_base64 = base64_encode( $pdf_content );
    
            wp_send_json_success( array( 'pdf' => $pdf_base64 ) );

        } catch ( Throwable $e ) {
            error_log( 'Bulk Export PDF Error: ' . $e->getMessage() );
            wp_send_json_error( 'Server Error: ' . $e->getMessage() );
        }
    }
}
