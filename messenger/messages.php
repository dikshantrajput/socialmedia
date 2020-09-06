<?php 
    include "../includes/connection.inc.php";
    include "../includes/header.inc.php";

    //pagination

    $per_page = 4;
    $count = 0;

    $selectquery = "select * from users";
    $stmt = $connection->prepare($selectquery);
    $stmt->execute();
    $count = $stmt->rowCount();
    
    $no_of_pages = ceil($count/$per_page);


?>
<div id="success" class="bg-success text-light">

</div>


<div class="mt-4 container col-lg-6 mx-auto table-responsive-sm">
    <table id="table" class="table table-bordered mt-2">
        <tr>
            <th>Username</th>
            <th>Status</th>
        </tr>
    </table>
    <div class="pagination-div text-center">
        <?php for($i=1;$i<=$no_of_pages;$i++){echo '<button class="mr-1 btn btn-dark pagination-btn" data-id="'.$i.'">'.$i.'</button>';}?>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <form method="post">
                <label class="form-label">To:</label>
                <select class="form-select mb-2" id="users">
                    <option value=""></option>
                </select>
                <label class="form-label">Message:</label>
                <input type="text"  class="form-control" name="message" id="message"><br>
                <input type="submit"  class="form-control btn btn-primary" name="submit" id="submit" value="Send">
            </form>
        </div>
    </div>
</div>

<div class="container">
    <h4 class="text-warning text-center mt-2"> Unread Messages<button id="read-btn" class="btn btn-dark text-light ml-3">Mark as read</button></h4>
    <div class="p-2 col-lg-4 col-lg-6 mx-auto bg-dark text-light text-left" id= "show-messages">
    </div>
</div>


<script>
$(document).ready(()=>{
    setInterval(()=>{
        updateStatus()
        status_table()
        loadMessages()
    },5000)

    const status_table = ()=>{
        let page=0
        page = document.querySelectorAll(".pagination-btn")
        Array.from(page).forEach((pg)=>{
            pg.addEventListener("click",(e)=>{
            let p = e.target.dataset.id
            $.ajax({
                url:'updateStatus.php',
                type:'post',
                data:{page:p},
                success:(data)=>{
                    $('#table').html(data)
                },
            })
        })
        })
    }

    const updateStatus = ()=>{
        $.ajax({
            url:'updateOnlineOffline.php',
            type:'post',
            success:(data)=>{
            },
        })

    }

    $.ajax({
        url:'updateStatus.php',
        type:'post',
        success:(data)=>{
            $('#table').html(data)
        },
    })


    status_table();
    
    const loadUsers = ()=>{
        $.ajax({
            url:"load_users.php",
            type:"post",
            success:(data)=>{
                $('#users').html(data)
            },
        })
    }
loadUsers();


    $('#submit').on("click",(e)=>{
        e.preventDefault();
        let to_id = $('#users').val()
        let message = $('#message').val()
        $.ajax({
            url:'sendMessage.php',
            type:'post',
            data:{to_id:to_id,message:message},
            success:(data)=>{
                $("#success").html(data)
                setInterval(()=>{
                    $("#success").hide()
                },2000);
            }
        })
        $('#message').val('')
    })

    const loadMessages = ()=>{
        $.ajax({
            url:"showMessages.php",
            type:"post",
            success:(data)=>{
                $('#show-messages').html(data)
            },
        })
    }
loadMessages();

    $('#read-btn').on("click",()=>{
        $.ajax({
            url:"hideMessages.php",
            type:"post",
        })
    })
})
</script>

<?php include "../includes/footer.inc.php";