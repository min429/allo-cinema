<!DOCTYPE html>
<html lang="ko">
<div id="headers"></div>

<head>
    <meta charset="UTF-8">
    <title>영화정보페이지</title>
    <link rel="stylesheet" href="../css/infostyle.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
</head>


<?php

                        include '../php/dbconfig.php';
                        $value = $_GET['value'];

                        session_start();
                        $_SESSION['mv_code'] = $value;
                        // 현재 URL에서 쿼리 파라미터 값을 읽어옴

                        // 데이터베이스에서 정보 가져오기
                        // 감독 정보와 영화 정보를 함께 가져오는 쿼리

                        $sql = "SELECT M.MV_name, M.Opening_date, M.Grade, M.MV_pic ,M.Run_Time, M.Audi_num, D.DIR_code, D.DIR_name ,M.Mv_Des ,D.DIR_pic
                        FROM Movie M
                        INNER JOIN Director D ON M.Dir_code = D.DIR_code
                        WHERE M.MV_code = '$value'";
                        $result = $conn->query($sql);

                        // 결과 출력
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                $MV_pic = $row['MV_pic'];
                                $movieName = $row['MV_name'];
                                $openingDate = $row['Opening_date'];
                                $grade = $row['Grade'];
                                $MV_pic = $row['MV_pic'];
                                $runningTime = $row['Run_Time'];
                                $audience = $row['Audi_num'];
                                $directorName = $row['DIR_name'];
                                $movieDescription = $row['Mv_Des'];
                                $directorPicture = $row['DIR_pic'];
                                $directorcode = $row['DIR_code'];


                            }
                        } else {
                            echo "영화를 찾을 수 없습니다.";
                        }
                        $conn->close();
                        ?>
<body>
    <div class="container">
        <div class="movie-info">
            <img id="movie-image" src=<?= $MV_pic ?>>
            <div class="movie-info-content">
                <h2>기본 정보</h2>
                <p class="white_text">제목: <span class="white_text" id="movie-title"> <?= $movieName ?></span></p>
                <p class="white_text">개봉: <span class="white_text" id="movie-release-date"><?= $openingDate ?></span></p>
                <p class="white_text">등급: <span class="white_text" id="movie-old"><?= $grade ?></span></p>
                <p class="white_text">러닝 타임: <span class="white_text" id="movie-running-time"><?= $runningTime ?></span></p>
                <p class="white_text">관객수: <span class="white_text" id="movie-audience"> <?= number_format($audience) ?>명</span></p>
            </div>
        </div>

    

        <div class="movie-info">
            <div class="movie-info-content">
                <h2>소개</h2>
                <p class="white_text" id="movie-intro"><?= $movieDescription ?></p>
            </div>
        </div>

        <div class="movie-info">
            <div class="movie-info-content">
                <h2 class="info_name">감독</h2>
                
                <div class="people_card">
                    <a href="info_dir.php?value=<?= $directorcode ?>" class="director-link">
                        <div class="thumb">
                            <img id="movie-image" src=<?= $directorPicture ?>>
                        
                        </div>

                        <div class="title_box">
                            <span class="sub_name" style="max-height: 4rem;">감독</span>
                            <strong class="people_name" id="movie-director" style="max-height: 4rem;">
                                <?= $directorName ?>
                            </strong>
                        </div>
                    </a>
                </div>
            </div>
        </div>



        <div class="movie-info">
            <div class="movie-info-content">
                <h2 class="info_name">출연</h2>
                <div class="movie-actor">
                <?php
                    include '../php/dbconfig.php';

                    // 영화 코드
                    $movieCode = $_GET['value'];

                    // 배우 정보를 가져오는 쿼리
                    $sql = "SELECT A.ACT_code, A.ACT_name, A.ACT_pic
                            FROM Actor A
                            INNER JOIN Enter E ON A.ACT_code = E.ACT_code
                            WHERE E.MV_code = '$movieCode'";
                    $result = $conn->query($sql);

                    // 결과 출력
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $actorCode = $row['ACT_code'];
                            $actorName = $row['ACT_name'];
                            $actorPicture = $row['ACT_pic'];


                            // 배우 카드 출력
                            echo '<div class="people_card">';
                            echo '<a href="info_act.php?value=' . $actorCode . '">';
                            echo '<div class="thumb">';
                            echo '<img src="' . $actorPicture . '" alt="사진">';
                            echo '</div>';
                            echo '<div class="title_box">';
                            echo '<span class="sub_name" style="max-height: 4rem;">출연 배우</span>';
                            echo '<strong class="people_name" style="max-height: 4rem;">' . $actorName . '</strong>';
                            echo '</div>';
                            echo '</a>';
                            echo '</div>';
                        }
                    } else {
                        echo '출연 배우 정보를 찾을 수 없습니다.';
                    }
                    $conn->close();
                ?>
                
                </div>
            </div>
        </div>


                <!-- 리뷰 섹션 -->
                <?php
                    include '../php/dbconfig.php';
                    $movieCode = $_GET['value'];
                    $sql = "SELECT U.USR_name, R.MV_code, R.rating, R.content 
                    FROM Review R
                    INNER JOIN User U ON R.USR_ID = U.USR_ID
                    WHERE R.MV_code = '$value'";

                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<div class="review-section">';
                        echo '<h2>리뷰</h2>';
                        echo '<ul class="review-list">';
                        while ($row = $result->fetch_assoc()) {
                            $username = $row['USR_name'];
                            $movieCode = $row['MV_code'];
                            $rating = $row['rating'];
                            $content = $row['content'];

                            echo '<li class="review-item">';
                            echo '<div class="name">' . $username . '</div>';
                            echo '<div class="ratingStars" id="rating_' . $username . '"></div>';
                            echo '<div class="content">' . $content . '</div>';
                            echo '</li><br>';
                            echo "<script>displayRating($rating, 'rating_$username');</script>";
                        }
                        echo '</ul>';
                        echo '</div>';
                    } else {
                        echo '리뷰가 없습니다.';
                    }
                    $conn->close();
                    ?>
        

                <form action="review-register.php" method="POST">
            <div class="comment-input">
                <br>
                <div class="white_text">별점</div>
                <div class="rating">
                    <input type="checkbox" class="star" id="star-1" name="rating" value="1">
                    <label for="star-1"></label>
                    <input type="checkbox" class="star" id="star-2" value="2">
                    <label for="star-2"></label>
                    <input type="checkbox" class="star" id="star-3" value="3">
                    <label for="star-3"></label>
                    <input type="checkbox" class="star" id="star-4" value="4">
                    <label for="star-4"></label>
                    <input type="checkbox" class="star" id="star-5" value="5">
                    <label for="star-5"></label>
                    </div>
                <textarea name="content" placeholder="어떤 소감을 느끼셨나요?"></textarea>
                <button type="submit" class="save-review-button">작성</button>
            </div>
        </form>

        
        </div>

       
    </div>
    <button id="scroll-to-top-button" title="맨 위로 이동" onclick="scrollToTop()">^</button>
</body>
<div id="footers"></div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js" crossorigin="anonymous"></script>

<script>
// 별점 표시를 위한 함수
window.onload = function() {
  function displayRating(ratingStars, containerId) {
      const ratingContainer = document.getElementById(containerId);
  
      if (ratingContainer) {
          for (let i = 0; i < 5; i++) {
              const star = document.createElement("span");
              star.className = "star";
              if (i < ratingStars) {
                  star.classList.add("filled");
              }
              star.textContent = "★";
              ratingContainer.appendChild(star);
          }
      }
  }
}
</script>


<script src="../js/jquery-3.7.0.min.js"></script>
<script type="text/javascript">

    $(document).ready(function () {

        $("#headers").load("header.html");
        $("#footers").load("footer.html");
        $("#side").load("side.html");
        $("#contents").load("contents-now.html");
        $("#slide").load("slide.html");
    });

</script>
<script src="../js/data.js"></script>