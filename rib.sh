#!/bin/bash
function potong_file() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file Tidak Ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mJumlah Yang Diinginkan: \e[0m' line_per_file
  if ! [[ "$line_per_file" =~ ^[0-9]+$ ]]; then
    echo -e "\e[1;31mInput tidak valid. Harap masukkan angka.\e[0m"
    return
  fi

  mkdir -p potongan_files

  total_lines=$(wc -l < "$input_file")
  file_counter=1

  while [ "$total_lines" -gt 0 ]; do
    lines_to_move=$((total_lines > line_per_file ? line_per_file : total_lines))
    sed -n "1,${lines_to_move}p" "$input_file" > "potongan_files/file_${file_counter}.txt"
    sed -i "1,${lines_to_move}d" "$input_file"
    total_lines=$((total_lines - lines_to_move))
    file_counter=$((file_counter + 1))
  done

  echo -e "\e[1;32mMemotong Line Selesai... \e[0m"
}

function ekstrak_domain() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan Nama File Baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  awk -F '/' '{ print $NF }' "$input_file" > "$output_file"

  echo -e "\e[1;32mEkstraksi Domain Selesai... \e[0m"
}

function hapus_baris_duplikat() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  sort "$input_file" | uniq > "$input_file.tmp"
  mv "$input_file.tmp" "$input_file"

  echo -e "\e[1;32mBaris duplikat dalam file $input_file telah dihapus.\e[0m"
}

function filter_domain() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan Nama File Baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  grep -Eo '([a-zA-Z0-9.-]+\.[a-zA-Z]{2,})' "$input_file" | \
  while read -r domain; do
    extension="${domain##*.}"

    if [[ "$extension" =~ ^(php|html|htm|xml|zip)$ ]]; then
      echo "File $domain diabaikan karena ekstensi $extension terdapat dalam daftar hitam."
    else
      echo "$domain" >> "$output_file"
    fi
  done

  echo -e "\e[1;32mFilter Domain Selesai... \e[0m"
}

function sort_by_alphabet() {
  read -p $'\e[1;34mMasukkan Nama File: \e[0m' input_file
  if [ ! -f "$input_file" ]; then
    echo -e "\e[1;31mFile $input_file tidak ditemukan.\e[0m"
    return
  fi

  read -p $'\e[1;34mMasukkan Nama File Baru: \e[0m' output_file_name
  if [ -z "$output_file_name" ]; then
    echo -e "\e[1;31mNama file baru tidak boleh kosong.\e[0m"
    return
  fi

  output_file="${output_file_name}.txt"

  sort "$input_file" > "$output_file"

  echo -e "\e[1;32mSort By Alphabet Selesai... \e[0m"
}

function merge_files() {
  read -p "Masukkan Nama File 1: " file1
  if [ ! -f "$file1" ]; then
    echo "File $file1 tidak ditemukan."
    return
  fi

  read -p "Masukkan Nama File 2: " file2
  if [ ! -f "$file2" ]; then
    echo "File $file2 tidak ditemukan."
    return
  fi

  echo "Proses Menggabungkan isi dari $file1 ke $file2 ."

  while read -r line; do
    echo "$line" >> "$file2"
  done < "$file1"

  echo "Penggabungan selesai. Hasil tersimpan di $file2."
}

function show_menu() {
  echo -e "\033[1;32mPowered By RibelCyberTeam (Mr.7Mind)\033[0m"
  echo -e "\033[1;32m:'######::'####:'##::::'##:'########::'##:::::::'########:'########::'#######:::'#######::'##::::::::'######::\033[0m"
  echo -e "\033[1;32m'##... ##:. ##:: ###::'###: ##.... ##: ##::::::: ##.....::... ##..::'##.... ##:'##.... ##: ##:::::::'##... ##:\033[0m"
  echo -e "\033[1;32m ##:::..::: ##:: ####'####: ##:::: ##: ##::::::: ##:::::::::: ##:::: ##:::: ##: ##:::: ##: ##::::::: ##:::..::\033[0m"
  echo -e "\033[1;32m. ######::: ##:: ## ### ##: ########:: ##::::::: ######:::::: ##:::: ##:::: ##: ##:::: ##: ##:::::::. ######::\033[0m"
  echo -e "\033[1;32m:..... ##:: ##:: ##. #: ##: ##.....::: ##::::::: ##...::::::: ##:::: ##:::: ##: ##:::: ##: ##::::::::..... ##:\033[0m"
  echo -e "\033[1;32m'##::: ##:: ##:: ##:.:: ##: ##:::::::: ##::::::: ##:::::::::: ##:::: ##:::: ##: ##:::: ##: ##:::::::'##::: ##:\033[0m"
  echo -e "\033[1;32m. ######::'####: ##:::: ##: ##:::::::: ########: ########:::: ##::::. #######::. #######:: ########:. ######::\033[0m"
  echo -e "\033[1;32m:......:::....::..:::::..::..:::::::::........::........:::::..::::::.......::::.......:::........:::......:::\033[0m"
  echo -e "\033[1;32m1. Memotong File\033[0m"
  echo -e "\033[1;32m2. Menghapus Duplikat\033[0m"
  echo -e "\033[1;32m3. Filter Sesuai Domain\033[0m"
  echo -e "\033[1;32m4. Sortir Berdasarkan Abjad\033[0m"
  echo -e "\033[1;32m5. Menggabungkan 2 List Text\033[0m"
  echo -e "\033[1;32m6. Ekstrak Domain Dari File\033[0m"
  echo -e "\033[1;32m7. Keluar\033[0m"
}

while true; do
  show_menu
  read -p $'\e[1;32mPilih menu: \e[0m' menu_choice

  case $menu_choice in
    1)
      potong_file
      ;;
    2)
      hapus_baris_duplikat
      ;;
    3)
      filter_domain
      ;;
    4)
      sort_by_alphabet
      ;;
    5)
	  merge_files
	  ;;
	6)
	  filter_domain
	  ;;
	7)
      echo -e "\e[1;33mTerima kasih, Follow github.com/Mr-7Mind \e[0m"
      exit 0
      ;;
    *)
      echo -e "\e[1;31mPilihan tidak valid. Harap pilih 1, 2, 3, 4, atau 5.\e[0m"
      ;;
  esac

  echo ""
done
