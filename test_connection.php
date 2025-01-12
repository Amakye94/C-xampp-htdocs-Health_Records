<div class="uploaded-files mt-4">
            <h2 class="text-secondary">Uploaded Files</h2>
            <h3 class="mt-3">Radiology Images</h3>
            <?php if (!empty($radiology_images)): ?>
                <?php foreach ($radiology_images as $row): ?>
                    <img src="<?php echo htmlspecialchars($row['file_path']); ?>" alt="Radiology Image" style="width:150px;">
                <?php endforeach; ?>
            <?php else: ?>
                <p>No radiology images uploaded.</p>
            <?php endif; ?>

            <h3 class="mt-4">Lab Results</h3>
            <?php if (!empty($lab_results)): ?>
                <?php foreach ($lab_results as $row): ?>
                    <a href="<?php echo htmlspecialchars($row['file_path']); ?>" target="_blank">View Lab Result</a><br>
                <?php endforeach; ?>
            <?php else: ?>
                <p>No lab results uploaded.</p>
            <?php endif; ?>
        </div>

        <!-- Output Section -->
        <div id="output" class="output-container" style="display: none;">
            <h2 class="text-success">Patient Details Overview</h2>
            <p><strong>Name:</strong> <span id="output_name"></span></p>
            <p><strong>Age:</strong> <span id="output_age"></span></p>
            <p><strong>Gender:</strong> <span id="output_gender"></span></p>
            <p><strong>Date of Birth:</strong> <span id="output_dob"></span></p>
            <p><strong>Immunization:</strong> <span id="output_immunization"></span></p>
            <p><strong>Allergies:</strong> <span id="output_allergies"></span></p>
        </div>
    </div>