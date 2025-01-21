// Use DBML to define your database structure

Table user {
  id integer [primary key]
  email varchar
  password varchar
  first_name varchar
  last_name varchar
  role varchar
  photo varchar
  created_at datetime
  updated_at datetime
}

Table type {
  id integer [primary key]
  title varchar
  created_at datetime
  updated_at datetime
}

Table road_trip {
  id integer [primary key]
  utilisateur_id integer
  type_id integer
  title varchar
  body text [note: 'Content of the post']
  date_start date
  date_end date
  status bool
  likes integer
  created_at datetime
  updated_at datetime
}

Table check_point {
  id integer [primary key]
  road_trip_id integer
  title varchar
  coordinate varchar
  date_start date
  date_end date
  created_at datetime
  updated_at datetime
}

Table photo {
  id integer [primary key]
  road_trip_id integer
  title varchar
  created_at datetime
  updated_at datetime
}

Ref: road_trip.utilisateur_id > user.id // many-to-one
Ref: road_trip.type_id > type.id // many-to-one
Ref: check_point.road_trip_id > road_trip.id // many-to-one
Ref: photo.road_trip_id > road_trip.id // many-to-one