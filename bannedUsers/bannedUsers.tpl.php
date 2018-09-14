<?php

    class bannedUsersTemplate extends template {
		
		public $bannedUsers = '
			<h3>{title}</h3>
			<table class="table">
				<thead>
					<tr>
						<th>User</th>
						<th>Reason</th>
						<th>Length</th>
					</tr>
				</thead>
				<tbody>
					{#each users}
						<tr>
							<td><a href="?page=profile&view={id}">{name}</a></td>
							<td></td>
							<td></td>
						</tr>
					{/each}
				</tbody>
			</table>';
        
    }

?>