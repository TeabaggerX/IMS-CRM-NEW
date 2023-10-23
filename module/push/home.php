<h1>Push Notifications</h1>

<div class="card-body">
    <div class="table-responsive">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Message</th>
                    <th>Landing Page URL</th>
                    <th>Image URL</th>
                    <th title="Action Button Title">CTA</th>
                    <th>Audience</th>
                    <th>Tags</th>
                    <th title="Time To Live">TTL</th>
                    <th>Schedule</th> <!-- date time timezone-->
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    /*foreach ($temps as $k) {
                        echo '
                        <tr id="row_'.$k->id.'">
                        <input type="hidden" class="form-control" id="'.$k->id.'_affiliate" placeholder="" name="affiliate" value="'.$k->affiliate.'">
                            <td>'.'</td>
                            <td>'.'</td>
                            <td>
                                <a href="index.php?module=template&page=temp_add_edit&id='.$k->id.'" class="btn btn-info btn-circle" alt="edit" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                
                                <input type="hidden" class="form-control" id="'.$k->id.'" placeholder="" name="affiliate_active" value="'.$k->affiliate_active.'">
                                <span id="active" data="'.$k->id.'" class="activeFlag btn btn-success'.$active.' btn-circle">
                                    <i class=" fas fa-check"></i>
                                </span>
                                <button type="submit" data="'.$k->id.'" class="deleteFlag btn btn-danger btn-circle" alt="delete" title="Delete"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>';
                    }*/
                ?>
            </tbody>
        </table>
    </div>
</div>
