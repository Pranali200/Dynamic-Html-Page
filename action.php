<?php

//action.php

if(isset($_POST['action']))
{
	$file = 'data.json';
	
	/*if($_POST['action'] == 'fetch_all')
	{
		$file_data = file_get_contents($file);

		$data = json_decode($file_data, true);

		$output = array();

		if(count($data) > 0)
		{
			$id_data = array_column($data, 'id');

			array_multisort($id_data, SORT_DESC, $data);

			foreach($data as $row)
			{
				$sub_array = array();

				$sub_array[] = $row['name'];

				$sub_array[] = $row['mobile'];

				$sub_array[] = $row['email_id'];

				$sub_array[] = '<button type="button" class="btn btn-warning btn-sm edit" data-id="'.$row["id"].'">Edit</button>&nbsp;<button type="delete" class="btn btn-danger btn-sm delete" data-id="'.$row["id"].'">Delete</button>';

				$output[] = $sub_array;
			}
		}

		$response = array(
			"draw"				=>	intval($_POST["draw"]),
			"recordsTotal"		=> 	count($data),
			"recordsFiltered"	=>	count($data),
			"data"				=>	$output
		);

		echo json_encode($response);
	}*/

	if($_POST['action'] == 'Add' || $_POST['action'] == 'Edit')
	{
		$error = array();

		$data = array();

		$data['id']	= time();

		if(empty($_POST['name']))
		{
			$error['name_error'] = ' Name is Required';
		}
		else
		{
			$data['name'] = trim($_POST['name']);
		}

		if(empty($_POST['mobile']))
		{
			$error['mobile_error'] = 'Mobile No is Required';
		}
		else
		{
			$data['mobile'] = trim($_POST['mobile']);
		}

		

		if(empty($_POST['email_id']))
		{
			$error['email_id_error'] = 'Email Id is required';
		}
		else
		{
			$data['email_id'] = trim($_POST['email_id']);
		}

		if(count($error) > 0)
		{
			$output = array(
				'error'		=>	$error
			);
		}
		else
		{
			if($_POST['action'] == 'Add')
			{
				$file_data = json_decode(file_get_contents($file), true);

				//print_r($file_data);

				$file_data[] = $data;

				file_put_contents($file, json_encode($file_data));
				$output = array(
					'success'	=>	'Data Added'
				);
			}

			if($_POST['action'] == 'Edit')
			{
				$file_data = json_decode(file_get_contents($file), true);

				$key = array_search($_POST['id'], array_column($file_data, 'id'));

				$file_data[$key]['name'] = $data['name'];
				$file_data[$key]['mobile'] = $data['mobile'];
				$file_data[$key]['email_id'] = $data['email_id'];
				

				file_put_contents($file, json_encode($file_data));

				$output = array(
					'success'	=>	'Data Edited'
				);

			}
		}

		echo json_encode($output);
	}

	if($_POST['action'] == 'fetch_single')
	{
		$file_data = json_decode(file_get_contents($file), true);

		$key = array_search($_POST['id'], array_column($file_data, 'id'));

		echo json_encode($file_data[$key]);
	}

	if($_POST['action'] == 'delete')
	{
		$file_data = json_decode(file_get_contents($file), true);

		$key = array_search($_POST['id'], array_column($file_data, 'id'));

		unset($file_data[$key]);

		file_put_contents($file, json_encode($file_data));

		echo json_encode(['success' => 'Data Deleted']);
	}
}

?>