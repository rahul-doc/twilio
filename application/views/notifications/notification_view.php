<div id="list">
    <h4>Your Message</h4><p><?php echo $mess?></p><br/>
    <p>Following are Recipients.</p>
    
    <ul> 
   <?php 
        foreach($item as $r):
            echo "<li>".$r->name."</li>";
        endforeach;
    ?>
    </ul>

</div>
